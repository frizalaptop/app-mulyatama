<?php

namespace App\Console\Commands;

use App\Traits\ServiceLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as SymfonyCommand;


class UpdateStatusBillboard extends Command
{
    use ServiceLogger;

    /**
     * Cara memanggil perintah artisan.
     *
     * @var string
     */
    protected $signature = 'billboard:update-status';

    /**
     * Deskripsi perintah.
     *
     * @var string
     */
    protected $description = 'Perbarui status billboard menjadi tersedia jika masa sewanya sudah berakhir.';

    /**
     * Eksekusi perintah console.
     */
    public function handle()
    {
        try {
            // Dapatkan tanggal hari ini
            $today = Carbon::today();

            // Cari billboard yang masa sewanya berakhir hari ini
            $billboardIds = $this->fetchBillboardExpiredIds($today);

            // Jika tidak ada billboard yang perlu diperbarui
            if ($billboardIds->isEmpty()) {
                // Tandai sebagai sukses tanpa perubahan
                $this->handleNoData();
                return SymfonyCommand::SUCCESS;
            }

            // Perbarui status billboard menjadi tersedia
            $this->updateBillboardStatus($billboardIds);

            // Tandai sebagai sukses dengan perubahan
            $this->handleSuccessCommand($billboardIds);
            return SymfonyCommand::SUCCESS;
        } catch (\Throwable $e) {
            // Tandai kesalahan dan log error yang terjadi
            $this->handleErrorCommand($e);
            return SymfonyCommand::FAILURE;
        }
    }

    private function fetchBillboardExpiredIds(Carbon $today)
    {
        return DB::table('billboards')
            ->where('status', 0) // Status non-aktif
            ->whereNotExists(function ($query) use ($today) {
                $query->select(DB::raw(1))
                    ->from('billboard_sewa')
                    ->whereRaw('billboard_sewa.billboard_id = billboards.id')
                    ->where('billboard_sewa.tgl_akhir', '>=', $today);
            })
            ->pluck('billboards.id');
    }

    private function handleNoData()
    {
        $message = 'Tidak ada billboard yang masa sewanya berakhir hari ini.';
        $this->info($message);
        $this->logSuccessCommand($this->signature, $message);
    }

    private function updateBillboardStatus($billboardIds)
    {
        DB::table('billboards')
            ->whereIn('id', $billboardIds)
            ->update([
                'status' => 1,
                'updated_at' => now(),
            ]);
    }

    private function handleSuccessCommand($billboardIds)
    {
        $count = $billboardIds->count();
        $message = "Berhasil memperbarui status {$count} billboard menjadi tersedia.";
        $this->info($message);
        $this->logSuccessCommand($this->signature, $message, [
            'count' => $count,
            'ids' => $billboardIds,
        ]);
    }

    private function handleErrorCommand($error)
    {
        $this->error('Terjadi kesalahan: ' . $error->getMessage());
        $this->logExceptionCommand($this->signature, $error);
    }
}
