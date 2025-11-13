<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Menangani semua log
 * @see app\Listeners\LogUserSensitiveDataChange.php 
 * @see app\Traits\HandlersException.php
 */
trait ServiceLogger
{
    /**
     * Log harian info perubahan data sensitif user.
     * @param mixed $event instance UserSensitiveDataChanged 
     * @return void
     */
    protected function logUserSensitiveDataChange($event)
    {
        Log::channel('daily')->info("Perubahan data sensitif user ID {$event->user->id}", [
            'changes' => $event->changes,
            'updated_by' => $event->updatedBy,
        ]);
    }

    /**
     * Log harian error umum yang tidak tertangani.
     * Dipanggil oleh handler exception app/Traits/HandlersException.php
     * @param Throwable $e base interface untuk object apapun yang dapat di throw melalui throw statement
     * @return void
     */
    protected function logExceptionHttp(Throwable $e): void
    {
        Log::channel('daily')->error('Unhandled exception: ' . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => request()->fullUrl() ?? null,
            'user_id' => auth()->user()->id ?? 'guest',
        ]);
    }

    /**
     * Log keberhasilan eksekusi command Artisan.
     * @param string $commandName nama perintah artisan
     * @param string $message pesan hasil proses
     * @param array $context (opsional) konteks perubahan seperti jumlah data yang berubah
     * @return void
     */
    protected function logSuccessCommand(string $commandName, string $message = '', array $context = []): void
    {
        Log::channel('daily')->info("[Command: {$commandName}] Sukses dijalankan.", array_merge([
            'message' => $message,
            'time' => now()->toDateTimeString(),
        ], $context));
    }

    /**
     * Log kegagalan eksekusi command Artisan
     * @param string $commandName nama perintah artisan
     * @param \Throwable $e instance Throwable
     * @param array $context (opsional) konteks perubahan seperti jumlah data yang berubah
     * @return void
     */
    protected function logExceptionCommand(string $commandName, Throwable $e, array $context = []): void
    {
        Log::channel('daily')->error("[Command: {$commandName}] Gagal dijalankan!", array_merge([
            'error' => $e->getMessage(),
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'time' => now()->toDateTimeString(),
        ], $context));
    }
}
