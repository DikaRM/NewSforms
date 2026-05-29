<?php

namespace App\Providers;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Url;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use App\Models\Jadwal;
use App\Models\Ujian;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Paginator::useTailwind();
        // Define rate limiter untuk API
         if (str_contains(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }
        RateLimiter::for('api', function ($job) {
            return Limit::perMinute(60)->by($job->user()?->id ?: $job->ip());
        });

        if (Schema::hasTable('jadwal') && Schema::hasTable('ujian')) {
            $this->updateUjianStatus();
        }
    }
    
    private function updateUjianStatus()
    {
        try {
            $now = Carbon::now();
            
            // Cari semua jadwal yang sudah selesai
            $jadwalsSelesai = Jadwal::where('waktu_selesai', '<', $now)
                ->with('ujian')
                ->get();
            
            foreach($jadwalsSelesai as $jadwal) {
                if($jadwal->ujian && $jadwal->ujian->status != 'done') {
                    $jadwal->ujian->status = 'done';
                    $jadwal->ujian->save();
                }
            }
        } catch (\Exception $e) {
            // Abaikan error jika tabel belum ada
            \Log::info('Tabel belum siap: ' . $e->getMessage());
        }
    }
}