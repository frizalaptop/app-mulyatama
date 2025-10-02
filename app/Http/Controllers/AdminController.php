<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Mengambil ui daftar user 
     * Role admin
     * @return \Illuminate\Contracts\View\View
     */
    public function userList()
    {
        return view('user.user-list');
    }

    /**
     * Mengambil data-tabel user
     * Role admin
     * @return \Illuminate\Http\JsonResponse
     */
    public function datatable()
    {
         $users = User::get();

        return response()->json([
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->active ? 
                        '<span class="lencana bg-primary">aktif</span>' : 
                        '<span class="lencana bg-danger">Non aktif</span>',
                    'role' => $user->role ?? '-',
                    'last_login_at' => $user->last_login_at,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                    'action' => '
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-dark" data-id="'.$user->id.'"  data-target="#modalEditUser" data-toggle="modal">Edit</button>
                            <button class="btn btn-sm btn-success" data-id="'.$user->id.'">Profil</button>
                        </div>
                        ',
                ];
            }),
        ]);
    }

    /**
     * Menambah data user
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm]
     * @return \Illuminate\Http\JsonResponse
     */
    public function addUser(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:100', 'min:3'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'max:100', 'min:8', 'confirmed'],

            'company'  => ['nullable', 'string', 'max:100'],
            'wa'       => ['nullable', 'string', 'max:15'],
            'telegram' => ['nullable', 'string', 'max:15'],
            'address'  => ['nullable', 'string'], 
        ]);

        DB::beginTransaction();

        try {
            // buat user
            $user = User::create([
                'name'     => $validated['name'],
                'email'    => $validated['email'],
                'password' => Hash::make($validated['password']),
                'active'   => $request->aktifasi == 'Aktif',
            ]);

            $profile = Profile::create([
                'pf_iduser'   => $user->id,
                'pf_company'  => $validated['company'] ?? null,
                'pf_wa'       => $validated['wa'] ?? null,
                'pf_telegram' => $validated['telegram'] ?? null,
                'pf_address'  => $validated['address'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User baru berhasil ditambahkan.',
                'user'    => $user,
            ]);

        } catch (\Throwable $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Gagal menambahkan user: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getUserById($id){
        $user = User::with('profile')->findOrFail($id);
        return response()->json([
            'success' => true,
            'message' => 'User ditemukan.',
            'user' => $user,
        ]);
    }

    /**
     * Mengubah data user
     * Role admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm]
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:100|min:3',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'company' => 'nullable|string|max:255',
            'wa' => 'nullable|string|max:15',
            'telegram' => 'nullable|string|max:255',
            'address' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $user->name = $validated['name'];
            $user->email = $validated['email'];
            $user->active = $request->aktifasi === 'Aktif';

            if (!empty($validated['password'])) {
                $user->password = Hash::make($validated['password']);
            }

            $user->save();

            $profileData = [
                'pf_company'  => $validated['company'] ?? null,
                'pf_wa'       => $validated['wa'] ?? null,
                'pf_telegram' => $validated['telegram'] ?? null,
                'pf_address'  => $validated['address'] ?? null,
            ];

            if ($user->profile) {
                $user->profile->update($profileData);
            } else {
                $user->profile()->create($profileData);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil diperbarui.',
                'user' => $user,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

}
