<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send the daily engagement reminder to all users every morning at 9:00 AM.
Schedule::command('youextractor:daily-reminders')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->onOneServer();
