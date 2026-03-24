<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
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