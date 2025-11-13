<?php

namespace App\Console\Commands;

use App\Traits\ServiceLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as SymfonyCommand;


class UpdateStatusBillboard extends Command
{
    use ServiceLogger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billboard:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Perbarui status billboard menjadi tersedia jika masa sewanya sudah berakhir.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $today = Carbon::today();

            $expiredBillboardIds = DB::table('billboard_sewa')
                ->join('billboards', 'billboards.id', '=', 'billboard_sewa.billboard_id')
                ->whereDate('billboard_sewa.tgl_akhir', '<', $today)
                ->where('billboards.status', 0)
                ->pluck('billboards.id');

            if ($expiredBillboardIds->isEmpty()) {
                $message = 'Tidak ada billboard yang masa sewanya berakhir hari ini.';
                $this->info($message);
                $this->logSuccessCommand($this->signature, $message);
                return SymfonyCommand::SUCCESS;
            }

            DB::transaction(function () use ($expiredBillboardIds) {
                DB::table('billboards')
                    ->whereIn('id', $expiredBillboardIds)
                    ->update([
                        'status' => 1,
                        'updated_at' => now(),
                    ]);
            });

            $count = $expiredBillboardIds->count();
            $message = "Berhasil memperbarui status {$count} billboard menjadi tersedia.";
            $this->info($message);

            $this->logSuccessCommand($this->signature, $message, [
                'count' => $count,
                'ids' => $expiredBillboardIds,
            ]);

            return SymfonyCommand::SUCCESS;

        } catch (\Throwable $e) {
            $this->error('Terjadi kesalahan: ' . $e->getMessage());
            $this->logExceptionCommand($this->signature, $e);
            return SymfonyCommand::FAILURE;
        }
    }
}
