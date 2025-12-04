<?php

namespace Database\Seeders;

use App\Models\Profile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class KlienUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $klienA = User::updateOrCreate(
            ['email' => 'klien_a@mail.com'],
            [
                'name' => 'Klien A',
                'password' => Hash::make('cirebon321'),
                'admin_buat' => "System",
                'admin_ubah' => "System",
            ]
        );

        Profile::create(['user_id' => $klienA->id]);

        $klienA->assignRole('Klien');

        $klienB = User::updateOrCreate(
            ['email' => 'klien_b@mail.com'],
            [
                'name' => 'Klien B',
                'password' => Hash::make('cirebon321'),
                'admin_buat' => "System",
                'admin_ubah' => "System",
            ]
        );

        Profile::create(['user_id' => $klienB->id]);

        $klienB->assignRole('Klien');
    }
}
