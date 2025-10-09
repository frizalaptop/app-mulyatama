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
    protected function logException(Throwable $e): void
    {
        Log::channel('daily')->error('Unhandled exception: ' . $e->getMessage(), [
            'exception' => get_class($e),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'url' => request()->fullUrl() ?? null,
            'user_id' => auth()->user()->id ?? 'guest',
        ]);
    }
}
