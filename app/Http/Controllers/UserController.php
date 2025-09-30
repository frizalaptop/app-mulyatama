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
        // $users = User::all();

        // $heads = [
        //     'ID',
        //     'Nama User',
        //     'Alamat Email',
        //     'Status',
        //     'Hak Akses',
        //     'Waktu Login',
        //     'Waktu Dibuat',
        //     'Waktu Diubah',
        //     ['label' => 'Aksi', 'no-export' => true],
        // ];

        // // siapkan array data untuk datatable
        // $data = [];
        // foreach ($users as $user) {
        //     $data[] = [
        //         $user->id,
        //         $user->name,
        //         $user->email,
        //         $user->status ?? '-',
        //         $user->role ?? '-',
        //         $user->last_login_at ? $user->last_login_at->format('d/m/Y H:i') : '-',
        //         $user->created_at->format('d/m/Y H:i'),
        //         $user->updated_at->format('d/m/Y H:i'),
        //         $user->id, // untuk kolom aksi (edit/delete)
        //     ];
        // }

        // $config = [
        //     'data' => $data,
        //     'order' => [[1, 'asc']],
        //     'columns' => [
        //         ['orderable' => true],
        //         ['orderable' => true],  
        //         ['orderable' => false], 
        //         ['orderable' => true],  
        //         ['orderable' => true],  
        //         ['orderable' => false], 
        //         ['orderable' => false], 
        //         ['orderable' => false], 
        //         ['orderable' => false],
        //     ],
        //     'dom' => <<<DOM
        //                 <"row mb-2"<"col-sm-2"B><"col-sm-2"l><"col-sm-8"f>>
        //                 <"row"<"col-sm-12"tr>>
        //                 <"row mt-2"<"col-sm-5"i><"col-sm-7"p>>
        //             DOM, 
        //     'buttons' => [
        //         [
        //             'text' => 'Add',
        //             'className' => 'btn btn-default btn-sm dt-button',
        //             'attr' => [
        //                 'data-target' => '#modalAdd',
        //                 'data-toggle' => 'modal',
        //             ],
        //         ],
        //         [
        //             'text' => 'Excel',
        //             'className' => 'btnExcel btn btn-default btn-sm dt-button',
        //         ],
                
        //     ],
        // ];

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
                    'aksi' => '<button class="btn btn-sm btn-dark" data-id="'.$user->id.'">Edit</button>',
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
