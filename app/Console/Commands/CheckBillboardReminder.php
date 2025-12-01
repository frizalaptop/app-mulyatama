<?php

namespace App\Console\Commands;

use App\Traits\ServiceLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

class CheckBillboardReminder extends Command
{
    use ServiceLogger;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billboard:check-billboard-reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pengecekan penyewaan billboard yang masuk kritetia pengiriman pengingat otomatis.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $today = Carbon::today();

            // Hari-hari reminder
            $reminderDays = [30, 14, 7, 1];

            // Menampung seluruh data (tanpa grup hari)
            $results = collect();

            foreach ($reminderDays as $day) {
                $targetDate = $today->copy()->addDays($day);
                $data = $this->fetchDataByDay($targetDate, $day);
                
                $results = $results->merge($data); // Gabung ke collection
                $this->info("Reminder {$day} hari: " . $data->count() . " data ditemukan.");
                
                Log::info($targetDate);

                // Logging
                $this->logSuccessCommand($this->signature, "Reminder {$day} hari ditemukan {$data->count()} data.", [
                    'day' => $day,
                    'count' => $data->count(),
                ]);
            }

            // Jika tidak ada data
            if ($results->isEmpty()) {
                $this->handleNoData();
                return SymfonyCommand::SUCCESS;
            }

            // Dispatch job tunggal, data sudah lengkap & flat
            $this->dispacthEmailJob($results);

            $this->info("Berhasil memproses reminder dan mengirim ke Queue.");
            return SymfonyCommand::SUCCESS;
        } catch (\Throwable $e) {
            $this->error("Terjadi kesalahan: " . $e->getMessage());
            $this->logExceptionCommand($this->signature, $e);
            return SymfonyCommand::FAILURE;
        }
    }

    private function fetchDataByDay(Carbon $targetDate, int $day)
    {
        return DB::table('billboard_sewa')
            ->join('billboards', 'billboards.id', '=', 'billboard_sewa.billboard_id')
            ->join('users', 'users.id', '=', 'billboard_sewa.user_id') // JOIN user
            ->whereDate('billboard_sewa.tgl_akhir', '=', $targetDate)
            ->select(
                'billboards.id as billboard_id',
                'billboards.judul',
                'billboards.lokasi',
                'billboard_sewa.tgl_awal',
                'billboard_sewa.tgl_akhir',
                'users.id as user_id',
                'users.name as user_name',
                'users.email as user_email',
                DB::raw($day . ' as sisa_hari') // tambahkan informasi berapa hari lagi
            )
            ->get();
    }
    private function dispacthEmailJob($data)
    {
        foreach ($data as $index => $item) {
            dispatch(new \App\Jobs\SendReminderEmailJob($item))
                ->delay(now()->addSeconds($index * 12));
        }
    }

    private function handleNoData()
    {
        $message = "Tidak ada billboard yang mendekati masa berakhir.";
        $this->info($message);
        $this->logSuccessCommand($this->signature, $message);
    }
}
