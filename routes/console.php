<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('billboard:update-status')
    ->daily()
    ->at('00:03');

Schedule::command('billboard:check-billboard-reminder')
    ->daily()
    ->at('00:05');

Schedule::command('queue:retry all')->everyFiveMinutes();
