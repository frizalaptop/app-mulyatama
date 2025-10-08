<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Menangani semua log
 */
trait ServiceLogger
{
    /**
     * Log harian info perubahan data sensitif user.
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
