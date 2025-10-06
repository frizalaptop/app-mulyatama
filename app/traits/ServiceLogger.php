<?php

namespace App\Traits;

use Illuminate\Support\Facades\Log;
use Throwable;

trait ServiceLogger
{
    protected function logException(Throwable $e, string $context)
    {
        Log::error("Error in {$context}: {$e->getMessage()}", [
            'trace' => $e->getTraceAsString(),
        ]);
    }
}
