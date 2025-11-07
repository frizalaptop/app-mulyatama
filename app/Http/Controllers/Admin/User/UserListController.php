<?php

namespace App\Http\Controllers\Admin\User;

use App\Events\UserSensitiveDataChanged;
use App\Http\Controllers\Controller;
use App\Http\Requests\AddUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Profile;
use App\Models\User;
use App\Traits\HandlersException;
use Illuminate\Http\Request;
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
    public function tabel (Request $request)
    {
        try {
            // Kolom yang memiliki fitur pengurutan
            $columns = [
                0 => 'id',
                1 => 'name',
                3 => 'email',
            ];

            $draw   = $request->get('draw');
            $start  = $request->get('start', 0);
            $length = $request->get('length', 10);
            $search = $request->input('search.value');
            $order  = $request->input('order')[0] ?? ['column' => 1, 'dir' => 'asc'];
            $customFilter = $request->input('columns', []);

            $orderColumn = $columns[$order['column']];
            $orderDir = $order['dir'];

            $query = User::query();

            if (!empty($search)) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
                });
            }

            foreach ($customFilter as $col) {
                $colName = $col['data'] ?? null;
                $colSearch = $col['search']['value'] ?? null;

                if ($colName && $colSearch !== null && $colSearch !== '') {
                    $query->where($colName, 'like', "%{$colSearch}%");
                }
            }

            $recordsTotal = User::count();
            $recordsFiltered = $query->count();

            $data = $query
                ->orderBy($orderColumn, $orderDir)
                ->offset($start)
                ->limit($length)
                ->get();
            
            $data = $data->map(function ($row) {
                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'email' => $row->email,
                    'status' => $row->active,
                    'role' => $row->getRoleNames()->first(),
                    'last_login_at' => $row->last_login_at?->format('Y-m-d H:i:s'),
                    'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                    'aksi'  => '<div class="btn-group" role="group">
                                <button class="btn btn-sm btn-dark btn-edit" data-id="'. $row->id .'" data-toggle="modal" data-target="#modalEditrow">Edit</button>
                                <button class="btn btn-sm btn-success btn-profile" data-id="'. $row->id .'">Profil</button>
                           </div>'
                ];
            });

            return response()->json([
                'draw' => intval($draw),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsFiltered,
                'data' => $data,
            ]);
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
