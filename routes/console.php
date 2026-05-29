<?php
use Illuminate\Support\Facades\Schedule;
use App\Models\Ruangan;
use Illuminate\Support\Str;

Schedule::call(function () {

    $generateUniqueKode = function () {
        do {
            $kode = strtoupper(Str::random(6));
        } while (Ruangan::where('kode', $kode)->exists());

        return $kode;
    };

    Ruangan::where(function($query) {
        $query->whereNull('kode_expired_at')
              ->orWhere('kode_expired_at', '<=', now());
    })->chunk(100, function ($rooms) use ($generateUniqueKode) {
        foreach ($rooms as $ruangan) {
            $ruangan->update([
                'kode' => $generateUniqueKode(),
                'kode_expired_at' => now()->addHour(),
            ]);
        }
    });
\Log::info('UPDATE KODE JALAN: ' . now());
})
->everyFiveMinutes()
->name('update-kode-ruangan')
->withoutOverlapping();