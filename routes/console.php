<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('booking:expire')
    ->everyMinute();

// Tandai booking yang jam mainnya sudah lewat sebagai completed & beri poin membership
Schedule::command('booking:complete')
    ->everyFiveMinutes();

// Hanguskan batch poin yang sudah lewat masa berlaku
Schedule::command('membership:expire-points')
    ->daily();

// Evaluasi naik/turun tier untuk customer yang siklusnya sudah berakhir
Schedule::command('membership:evaluate-tiers')
    ->daily();
