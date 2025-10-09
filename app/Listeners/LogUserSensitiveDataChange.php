<?php

namespace App\Listeners;

use App\Events\UserSensitiveDataChanged;
use App\Traits\ServiceLogger;

/**
 * Berperan sebagai listener untuk memantau event UserSensitiveDataChanged
 */
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
     * Handle event dan mengirim aktivitas ke ServiceLogger.
     * @param UserSensitiveDataChanged $event instance event
     */
    public function handle(UserSensitiveDataChanged $event): void
    {
        $this->logUserSensitiveDataChange($event);
    }
}
