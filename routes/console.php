<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Console\Commands\FreeSeat;
use App\Console\Commands\Overstay;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('free:seat')->dailyAt('00:30');
Schedule::command('app:user-disabled')->dailyAt('00:45');
Schedule::command('overstay:detect')->everyFifteenMinutes();
