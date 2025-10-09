<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

/**
 * Hanya menangani interaksi langsung dengan database tabel `users` dan cara mengambil datanya
 * Tidak boleh memiliki dependency terhadap Controller atau Service
 * @see app\Services\UserListService.php
 * @see app\Services\StatistikService.php
 */
class UserRepository
{

    /**
     * Mengambil data semua user
     * @return \Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function getAll()
    {
        return User::all();
    }

    /**
     * Mengambil data suatu user berdasarkan id
     * @param mixed $id user id
     * @return User|\Illuminate\Database\Eloquent\Collection<int, User>
     */
    public function find($id)
    {
        return User::findOrFail($id);
    }   

    /**
     * Mengambil jumlah user tersimpan
     * @return int
     */
    public function getAllCount(): int
    {
        return User::count();
    }

    /**
     * Mengambil jumlah user dengan status aktif
     * @return int
     */
    public function getActiveCount(): int
    {
        return User::where('active', true)->count();
    }

    /**
     * Mengambil jumlah user dengan status non aktif
     * @return int
     */
    public function getInactiveCount(): int
    {
        return User::where('active', false)->count();
    }

    /**
     * Menyimpan data user baru
     * @param array $data data user yang ingin disimpan
     * @return User
     */
    public function createUser(array $data): User
    {
        return User::create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'password' => Hash::make($data['password']),
            'active'   => $data['aktivasi'] === 'Aktif',
        ]);
    }

    /**
     * Memperbarui data user
     * Mengembalikan instance User
     * @param \App\Models\User $user instance User
     * @param array $data data user yang ingin diperbarui
     * @return User
     */
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