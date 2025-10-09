<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;

/**
 * Hanya menangani interaksi langsung dengan database tabel `user_profile` dan cara mengambil datanya
 * Tidak boleh memiliki dependency terhadap Controller atau Service
 * @see app\Services\UserListService.php
 */
class ProfileRepository{

    /**
     * Mengambil data profile berdasarkan user id
     * @param mixed $userId user id
     * @return Profile
     */
    public function findByUserId($userId): ?Profile
    {
        return Profile::where('user_id', $userId)->firstOrFail();
    }

    /**
     * Menambah data profile baru
     * @param \App\Models\User $user instance User
     * @param array $data data profile yang dibutuhkan
     * @return Profile
     */
    public function createProfile(User $user, array $data): Profile
    {
        return Profile::create([
            'user_id'    => $user->id,
            'perusahaan' => $data['perusahaan'] ?? null,
            'whatsapp'   => $data['whatsapp'] ?? null,
            'telegram'   => $data['telegram'] ?? null,
            'alamat'     => $data['alamat'] ?? null,
        ]);
    }

    /**
     * Memperbarui data profile
     * @param \App\Models\User $user instance User
     * @param array $data data profile yang dibutuhkan
     * @return void
     */
    public function updateProfile(User $user, array $data): void
    {
        $profileData = [
            'perusahaan' => $data['perusahaan'] ?? null,
            'whatsapp'   => $data['whatsapp'] ?? null,
            'telegram'   => $data['telegram'] ?? null,
            'alamat'     => $data['alamat'] ?? null,
        ];

        $user->profile->update($profileData);
    }
}