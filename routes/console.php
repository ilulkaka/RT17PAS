<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
//use Illuminate\Console\Scheduling\Schedule;


Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();
//Schedule::command('sanctum:prune-expired --hours=24')->daily();

