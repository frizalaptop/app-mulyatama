<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BillboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('billboards')->insert([
            [
                'judul' => 'Baliho A Yani',
                'area' => 'Kota Cirebon',
                'lokasi' => 'Jl. A. Yani By Pass - Cirebon',
                'status' => true,
                'aktif' => true,
                'keterangan' => null,
                'jenis' => 'Backlight',
                'lebar' => 10,
                'panjang' => 5,
                'unit' => 1,
                'latitude' => -6.7408276,
                'longitude' => 108.5547032,
                'gambar' => null,
                'admin_buat' => 'Wawan',
                'admin_ubah' => 'Wawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Billboard Kartini',
                'area' => 'Kabupaten Cirebon',
                'lokasi' => 'Jl. Kartini No.45 - Kabupaten Cirebon',
                'status' => false,
                'aktif' => true,
                'keterangan' => null,
                'jenis' => 'Frontlight',
                'lebar' => 8,
                'panjang' => 4,
                'unit' => 2,
                'latitude' => -6.7354102,
                'longitude' => 108.5532417,
                'gambar' => null,
                'admin_buat' => 'Wawan',
                'admin_ubah' => 'Wawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'judul' => 'Spanduk Gronggong',
                'area' => 'Kuningan',
                'lokasi' => 'Jl. Raya Gronggong KM 3 - Kuningan',
                'status' => true,
                'aktif' => false,
                'keterangan' => 'Perlu perawatan dan pengecekan struktur tiang.',
                'jenis' => 'Street Sign',
                'lebar' => 6,
                'panjang' => 3,
                'unit' => 1,
                'latitude' => -6.7320113,
                'longitude' => 108.5619822,
                'gambar' => null,
                'admin_buat' => 'Wawan',
                'admin_ubah' => 'Wawan',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
