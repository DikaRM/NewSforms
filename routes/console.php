<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\Ruangan;
use Illuminate\Support\Str;
use Carbon\Carbon;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');


Schedule::call(function () {
    $expiredRooms = Ruangan::where(function($query) {
        $query->whereNull('kode_expired_at')
              ->orWhere('kode_expired_at', '<=', now());
    })->get();

    foreach ($expiredRooms as $ruangan) {
        $ruangan->update([
            'kode' => strtoupper(Str::random(6)),
            'kode_expired_at' => now()->addHour(),
        ]);
    }
})->everyFiveMinutes(); // Cek setiap 5 menit sudah cukup untuk menghemat resource