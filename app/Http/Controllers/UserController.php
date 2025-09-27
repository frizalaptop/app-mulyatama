<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function userList()
    {
        $users = User::paginate(5);

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

        $btnEdit = '<button class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </button>';
        $btnDelete = '<button class="btn btn-xs btn-default text-danger mx-1 shadow" title="Delete">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>';
        $btnDetails = '<button class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                        <i class="fa fa-lg fa-fw fa-eye"></i>
                    </button>';

        // Ubah collection User jadi array datatable
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
                $btnEdit . $btnDelete . $btnDetails,
            ];
        }

        $config = [
            'data' => $data,
            'order' => [[1, 'asc']],
            'columns' => [null, null, null, null, null, null, null, null, ['orderable' => false]],
        ];

        return view('user.user-list', compact('users', 'heads', 'config'));
    }
}
