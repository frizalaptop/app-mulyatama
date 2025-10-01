<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;


class UserController extends Controller
{
    public function userList()
    {
        return view('user.user-list');
    }

    public function datatable()
    {
         $users = User::get(['id', 'name', 'email', 'active']);

        return response()->json([
            'data' => $users->map(function($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'status' => $user->active ? 'Aktif' : 'Nonaktif',
                    'aksi' => '<button class="btn btn-sm btn-dark" data-id="'.$user->id.'"  data-target="#modalEditUser" data-toggle="modal">Edit</button>',
                ];
            }),
        ]);
    }

    public function userProfile()
    {
        return view('user.user-profile');
    }

    public function updateProfile(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
    }

}
