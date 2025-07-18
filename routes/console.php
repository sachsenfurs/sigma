<?php

use App\Jobs\SendReminders;
use App\Jobs\ShiftQueueDailySummary;
use App\Jobs\ShiftQueueReminders;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::job(ShiftQueueDailySummary::class)->dailyAt("09:00");
Schedule::job(ShiftQueueReminders::class)->everyMinute();
Schedule::job(SendReminders::class)->everyMinute();
Schedule::command("lassie:sync")->everyFifteenMinutes();
