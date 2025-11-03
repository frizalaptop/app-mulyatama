<?php

namespace App\Http\Controllers\Admin\User;

use App\Events\UserSensitiveDataChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Profile;
use App\Models\User;
use App\Traits\HandlersException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Throwable;

/**
 * Controller khusus admin untuk mengelola data user
 */
class UserListController extends Controller
{

    use HandlersException;

    /**
     * Mengambil view daftar user 
     * @return \Illuminate\Contracts\View\View|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     */
    public function index ()
    {
        try {
            $data = ['title' => 'User List'];
            return view('admin.user.user-list', $data);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data-tabel user
     * @return \Illuminate\Http\JsonResponse
     */
    public function tabel ()
    {
        try {
            $users = User::all();
            $data = $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->active,
                    'role' => $user->getRoleNames()->first(),
                    'last_login_at' => $user->last_login_at?->format('Y-m-d H:i:s'),
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                    'aksi'  => '<div class="btn-group" role="group">
                                <button class="btn btn-sm btn-dark btn-edit" data-id="'. $user->id .'" data-toggle="modal" data-target="#modalEditUser">Edit</button>
                                <button class="btn btn-sm btn-success btn-profile" data-id="'. $user->id .'">Profil</button>
                           </div>'
                ];
            });
            return response()->json(['data' => $data]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengambil data user berdasarkan id
     * @param mixed $id user id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getId ($id) 
    {
        try {
            $user = User::findOrFail($id);

            $user->load('profil');
            
            $roleName = $user->getRoleNames()->first();

            $user->role = $roleName;

            return response()->json([
                'success' => true,
                'message' => 'User ditemukan.',
                'user' => $user,
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }

    /**
     * Menambah data user
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @return \Illuminate\Http\JsonResponse
     */
    public function simpan (AddUserRequest $request)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($data) {
                // 1️ Buat user baru
                $user = User::create([
                    'name'     => $data['name'],
                    'email'    => $data['email'],
                    'password' => Hash::make($data['password']),
                    'active'   => $data['aktivasi'] === 'Aktif',
                ]);

                // 2️ Buat profil untuk user tersebut
                Profile::create([
                    'user_id'    => $user->id,
                    'perusahaan' => $data['perusahaan'] ?? null,
                    'whatsapp'   => $data['whatsapp'] ?? null,
                    'telegram'   => $data['telegram'] ?? null,
                    'alamat'     => $data['alamat'] ?? null,
                ]);

                // 3️ Tambahkan role ke user
                $user->assignRole($data['role']);
            });
            return response()->json([
                'success' => true,
                'message' => 'User baru berhasil ditambahkan.',
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e);
        }
    }

    /**
     * Mengubah data user
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm, perusahaan, whatsapp, telegram, alamat, aktivasi, role]
     * @param mixed $id user id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update (UpdateUserRequest $request, $id)
    {
        try {
            $data = $request->validated();

            DB::transaction(function () use ($id, $data) {
                $user = User::findOrFail($id);
                $oldEmail = $user->email;

                $user->name = $data['name'];
                $user->email = $data['email'];

                if (!empty($data['aktivasi'])) {
                    $user->active = $data['aktivasi'] === 'Aktif';
                }

                $changes = [];

                if (!empty($data['password'])) {
                    $user->password = Hash::make($data['password']);
                    $changes['password'] = 'updated';
                }

                $user->save();

                $profile = $user->profil;
                if ($profile) {
                    $profile->update([
                        'perusahaan' => $data['perusahaan'] ?? null,
                        'whatsapp'   => $data['whatsapp'] ?? null,
                        'telegram'   => $data['telegram'] ?? null,
                        'alamat'     => $data['alamat'] ?? null,
                    ]);
                }

                $user->syncRoles($data['role']);

                if ($user->email !== $oldEmail) {
                    $changes['email'] = [
                        'old' => $oldEmail,
                        'new' => $user->email,
                    ];
                }

                if (!empty($changes)) {
                    DB::afterCommit(function () use ($user, $changes) {
                        event(new UserSensitiveDataChanged($user, $changes, Auth::user()->id));
                    });
                }
            });
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
            ]);
        } catch (Throwable $e) {
            return $this->handleException($e, 'User tidak ditemukan');
        }
    }
}
