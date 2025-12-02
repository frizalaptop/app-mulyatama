<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Billboard>
 */
class BillboardFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'judul' => $this->faker->words(2, true), // contoh: "Billboard A"
            'area' => $this->faker->randomElement(['Kota Cirebon', 'Kabupaten Cirebon', 'Kuningan', 'Majalengka', 'Indramayu']),
            'lokasi' => 'Jl. ' . $this->faker->streetName() . ' - ' . $this->faker->city(),
            'status' => 1,
            'aktif' => 1,
            'keterangan' => $this->faker->optional()->sentence(),
            'jenis' => $this->faker->randomElement(['Backlight', 'Frontlight', 'Street Sign']),
            'lebar' => $this->faker->randomFloat(1, 4, 12),
            'panjang' => $this->faker->randomFloat(1, 3, 8),
            'unit' => $this->faker->numberBetween(1, 5),
            'latitude' => $this->faker->latitude(-6.8, -6.6),
            'longitude' => $this->faker->longitude(108.4, 108.6),
            'gambar' => null,
            'admin_buat' => $this->faker->randomElement(['Wawan', 'Admin', 'Friza']),
            'admin_ubah' => $this->faker->randomElement(['Wawan', 'Admin', 'Friza']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
