<?php

use Spatie\LaravelSettings\Migrations\SettingsMigration;

return new class extends SettingsMigration
{
    public function up(): void
    {
        $this->migrator->add('reminder.Klien', [7, 3, 1]);
        $this->migrator->add('reminder.Admin', [7, 3, 1]);
    }

    public function down(): void
    {
        $this->migrator->delete('reminder.klien');
        $this->migrator->delete('reminder.admin');
    }
};
