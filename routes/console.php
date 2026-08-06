<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Jadwal pengiriman otomatis WA reminder H+7 perjalanan dinas belum bayar (setiap jam 08:00 WIB)
Schedule::command('travel:send-reminder')->dailyAt('08:00');
