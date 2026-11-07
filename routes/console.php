<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send the reminder email every 3 days at 9:00 AM (UTC) to users who haven't unsubscribed.
// Counting whole days since 1970 keeps the 3-day rhythm steady across month ends.
Schedule::command('youextractor:daily-reminders')
    ->dailyAt('09:00')
    ->when(fn () => intdiv(now()->timestamp, 86400) % 3 === 0)
    ->withoutOverlapping()
    ->onOneServer();
