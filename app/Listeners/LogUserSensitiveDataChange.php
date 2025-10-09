<?php

namespace App\Listeners;

use App\Events\UserSensitiveDataChanged;
use App\Traits\ServiceLogger;


class LogUserSensitiveDataChange
{

    use ServiceLogger;

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
        $this->logUserSensitiveDataChange($event);
    }
}
