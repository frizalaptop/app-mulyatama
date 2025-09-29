<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userList()
    {
        $users = User::all();

        $heads = [
            'ID',
            'Nama User',
            'Alamat Email',
            'Status',
            'Hak Akses',
            'Waktu Login',
            'Waktu Dibuat',
            'Waktu Diubah',
            ['label' => 'Aksi', 'no-export' => true],
        ];

        // siapkan array data untuk datatable
        $data = [];
        foreach ($users as $user) {
            $data[] = [
                $user->id,
                $user->name,
                $user->email,
                $user->status ?? '-',
                $user->role ?? '-',
                $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-',
                $user->created_at->format('d/m/Y H:i'),
                $user->updated_at->format('d/m/Y H:i'),
                $user->id, // untuk kolom aksi (edit/delete)
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [
                ['orderable' => false],
                ['orderable' => true],  
                ['orderable' => false], 
                ['orderable' => true],  
                ['orderable' => true],  
                ['orderable' => false], 
                ['orderable' => false], 
                ['orderable' => false], 
                ['orderable' => false],
            ],
            'dom' => '<"d-flex justify-content-between mb-2"Blf>rtip', // lf=length & filter, B=buttons
            'buttons' => [
                [
                    'text' => 'Add',
                    'className' => 'btn btn-outline-dark btn-sm',
                    'attr' => [
                        'data-target' => '#modalAdd',
                        'data-toggle' => 'modal',
                    ],
                ],
                [
                    'text' => 'Excel',
                    'className' => 'btn btn-outline-dark btn-sm',
                ],
            ],
        ];

        return view('user.user-list', compact('users', 'heads', 'config'));
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
