<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class AdminController extends Controller
{
    /**
     * Menambah data user oleh admin
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

    /**
     * Mengubah data user oleh admin
     * @param \Illuminate\Http\Request $request [name, email, password, password_confirm]
     * @param mixed $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
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
