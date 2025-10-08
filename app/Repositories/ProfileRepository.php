<?php

namespace App\Repositories;

use App\Models\Profile;
use App\Models\User;

class ProfileRepository{

    public function findByUserId($userId): ?Profile
    {
        return Profile::where('user_id', $userId)->firstOrFail  ();
    }

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