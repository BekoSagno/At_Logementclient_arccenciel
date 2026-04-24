<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Planifier la publication automatique des annonces programmées
Schedule::command('listings:publish-scheduled')
    ->everyMinute()
    ->withoutOverlapping()
    ->runInBackground();
