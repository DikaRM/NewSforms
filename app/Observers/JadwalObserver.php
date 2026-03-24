<?php

namespace App\Observers;

use App\Models\Jadwal;
use App\Models\Ujian;

class JadwalObserver
{
    /**
     * Handle the Jadwal "retrieved" event.
     */
    public function retrieved(Jadwal $jadwal)
    {
        $this->checkAndUpdateUjianStatus($jadwal);
    }
    
    /**
     * Handle the Jadwal "saving" event.
     */
    public function saving(Jadwal $jadwal)
    {
        $this->checkAndUpdateUjianStatus($jadwal);
    }
    
    private function checkAndUpdateUjianStatus(Jadwal $jadwal)
    {
        $now = now();
        
        // Cek apakah jadwal sudah selesai
        if($now > $jadwal->waktu_selesai) {
            // Update status ujian
            $ujian = $jadwal->ujian;
            if($ujian && $ujian->status != 'done') {
                $ujian->status = 'done';
                $ujian->save();
            }
        }
    }
}