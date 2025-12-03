<?php

namespace App\Settings;

use Spatie\LaravelSettings\Settings;

class ReminderSettings extends Settings
{
    public array $klien;
    public array $admin;

    public static function group(): string
    {
        return 'reminder';
    }
}