<?php

use App\Console\Commands\CleanOldNotifications;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Hapus notifikasi lama >30 hari, setiap hari pukul 02:00
Schedule::command(CleanOldNotifications::class)->dailyAt('02:00');
