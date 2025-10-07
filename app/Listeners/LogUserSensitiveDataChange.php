<?php

namespace App\Listeners;

use App\Events\UserSensitiveDataChanged;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;


class LogUserSensitiveDataChange
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserSensitiveDataChanged $event): void
    {
        Log::channel('daily')->info("Perubahan data sensitif user ID {$event->user->id}", [
            'changes' => $event->changes,
            'updated_by' => $event->updatedBy,
        ]);
    }
}
