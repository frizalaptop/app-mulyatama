<?php

namespace App\Repositories;

use App\Models\User;

/**
 * Hanya menangani interaksi langsung dengan database tabel `model_has_roles` dan cara mengambil datanya
 * Tidak boleh memiliki dependency terhadap Controller atau Service
 * @see app\Services\UserListService.php
 * @see app\Services\StatistikService.php
 */
class RoleRepository {

    /**
     * Menambah role pada user terkait
     * @param \App\Models\User $user instance User
     * @param string $role role yang akan dicantumkan ke user
     * @return User
     */
    public function assignRole(User $user, string $role): User
    {
        return $user->assignRole($role);
    }

    /**
     * Memperbarui/menimpa role lama ke baru dari user terkait
     * @param \App\Models\User $user instance user
     * @param string $role role yang akan diubah dari user
     * @return User
     */
	public function syncRole(User $user, string $role): User
    {
        return $user->syncRoles($role);
    }

    /**
     * Mengambil satu data role dari user terkait
     * @param \App\Models\User $user instance User
     * @return \Illuminate\Database\Eloquent\Collection
     */
	public function getRoleName(User $user)
	{
		return $user->getRoleNames()->first();
	}

    /**
     * Mengambil jumlah data role tertentu
     * @param string $role role yang akan dicari
     * @return int
     */
	public function getRoleCount(string $role): int
    {
        return User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->count();
    }
}