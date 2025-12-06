<?php

namespace App\Console\Commands;

use App\Traits\ServiceLogger;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

/**
 * Command untuk pengecekan reminder
 * remender tersedia: email
 * cara menjalankan "php artisan billboard:check-billboard-reminder"
 * @see routes\console.php dipanggil oleh task scheduller
 */
class CheckBillboardReminder extends Command
{
    use ServiceLogger;

    /**
     * Cara memanggil perintah artisan.
     *
     * @var string
     */
    protected $signature = 'billboard:check-billboard-reminder';

    /**
     * Deskripsi perintah.
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
            // Dapatkan tanggal hari ini
            $today = Carbon::today();

            // Dapatkan setting hari-hari reminder dari database
            $reminderDays = $this->fetchDaySettings();

            // Menampung seluruh data dari iterasi
            $results = collect();

            // Cek sisa hari sewa untuk setiap hari-hari reminder
            foreach ($reminderDays as $day) {
                $targetDate = $today->copy()->addDays($day);
                $data = $this->fetchDataByDay($targetDate, $day);
                
                $results = $results->merge($data);
                $this->info("Reminder {$day} hari: " . $data->count() . " data ditemukan.");

                $this->logSuccessCommand($this->signature, "Reminder {$day} hari ditemukan {$data->count()} data.", [
                    'day' => $day,
                    'count' => $data->count(),
                ]);
            }

            // Jika tidak ada data
            if ($results->isEmpty()) {
                // Tandai sebagai sukses tanpa pembuatan job
                $this->handleNoData();
                return SymfonyCommand::SUCCESS;
            }

            // Dispatch job & tandai command sukses
            $this->dispacthEmailJob($results);
            return SymfonyCommand::SUCCESS;
        } catch (\Throwable $e) {
            // Tandai kesalahan dan log error yang terjadi
            $this->handleErrorCommand($e);
            return SymfonyCommand::FAILURE;
        }
    }

    private function fetchDaySettings()
    {
        $reminderSetting = DB::table('settings')
            ->where('group', 'reminder')
            ->where('name', 'klien')
            ->value('payload');
        return $reminderSetting ? json_decode($reminderSetting, true) : [30, 14, 7, 1];
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

    private function handleNoData()
    {
        $message = "Tidak ada billboard yang mendekati masa berakhir.";
        $this->info($message);
        $this->logSuccessCommand($this->signature, $message);
    }

    private function dispacthEmailJob($data)
    {
        foreach ($data as $index => $item) {
            dispatch(new \App\Jobs\SendReminderEmailJob($item))
                ->delay(now()->addSeconds($index * 12));
        }
        $this->info("Berhasil memproses reminder dan mengirim ke Queue.");
    }

    private function handleErrorCommand($error)
    {
        $this->error("Terjadi kesalahan: " . $error->getMessage());
        $this->logExceptionCommand($this->signature, $error);
    }
}
