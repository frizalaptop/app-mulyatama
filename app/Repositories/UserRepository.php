<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserRepository
{
    public function getAll()
    {
        return User::all();
    }

    public function find($id)
    {
        return User::findOrFail($id);
    }   

    public function getAllCount(): int
    {
        return User::count();
    }

    public function getActiveCount(): int
    {
        return User::where('active', true)->count();
    }

    public function getInactiveCount(): int
    {
        return User::where('active', false)->count();
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'active'   => $data['aktivasi'] === 'Aktif',
        ]);
    }

    public function updateUser(User $user, array $data): User
    {
        $user->name = $data['name'];
        $user->email = $data['email'];
        $user->active = $data['aktivasi'] === 'Aktif';

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        return $user;
    }

    
}