<?php

namespace App\Repositories;

use App\Models\User;

class RoleRepository {
	public function assignRole(User $user, string $role): User
    {
        return $user->assignRole($role);
    }

	public function syncRole(User $user, string $role): User
    {
        return $user->syncRoles($role);
    }

	public function getRoleName(User $user)
	{
		return $user->getRoleNames()->first();
	}

	public function getRoleCount(string $role): int
    {
        return User::whereHas('roles', function ($q) use ($role) {
            $q->where('name', $role);
        })->count();
    }
}