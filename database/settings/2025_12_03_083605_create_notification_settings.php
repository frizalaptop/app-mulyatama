<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('notification.remember_day', 7);
    }

    public function down(): void
    {
        $this->migrator->delete('notification.remember_day');
    }
};
