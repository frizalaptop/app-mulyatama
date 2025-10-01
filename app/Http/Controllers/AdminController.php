<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
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
                    'action' => '<button class="btn btn-sm btn-dark" data-id="'.$user->id.'"  data-target="#modalEditUser" data-toggle="modal">Edit</button><button class="btn btn-sm btn-success" data-id="'.$user->id.'">Profil</button>',
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
        ]);

        $data = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            ...($request->aktifasi != 'Aktif'
            ? ['active' => false] 
            : [])
        ];

        $user = User::create($data);

        return response()->json([
            'success' => true,
            'message' => 'User baru berhasil ditambahkan.',
            'user' => $user,
        ]);
    }

    public function getUserById($id){
        $user = User::findOrFail($id);
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
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        
        $request->aktifasi == 'Aktif' ? $user->active = true : $user->active = false; 

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User berhasil diperbarui.',
            'user' => $user,
        ]);
    }

}
