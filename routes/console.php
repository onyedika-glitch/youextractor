<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Send reminders at 9:00 AM (UTC) in small batches: 20 users a day, so we stay well under
// the email provider's daily limit. A user gets at most one reminder every 3 days.
Schedule::command('youextractor:daily-reminders --limit=20')
    ->dailyAt('09:00')
    ->withoutOverlapping()
    ->onOneServer();
