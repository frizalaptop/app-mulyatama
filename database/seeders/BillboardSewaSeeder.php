<?php

namespace Database\Seeders;

use App\Models\Billboard;
use App\Models\BillboardSewa;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BillboardSewaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $clientIds = [2, 3];
        $periodeOptions = [1, 3, 7];
        
        // Ambil semua billboard
        $billboards = Billboard::all();
        
        foreach ($billboards as $billboard) {
            $days = $periodeOptions[array_rand($periodeOptions)];
            $tglAwal = Carbon::today();
            $tglAkhir = $tglAwal->copy()->addDays($days);
            
            BillboardSewa::create([
                'billboard_id' => $billboard->id,
                'user_id' => $clientIds[array_rand($clientIds)],
                'periode' => $days,
                'tgl_awal' => $tglAwal->toDateString(),
                'tgl_akhir' => $tglAkhir->toDateString(),
                'admin_buat' => 'System',
                'admin_ubah' => 'System',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
