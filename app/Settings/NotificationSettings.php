<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class NotificationSettings extends Settings
{
    public int $remember_day;

    public static function group(): string
    {
        return 'notification';
    }
}