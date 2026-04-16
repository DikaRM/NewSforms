<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusAndTimeToAbsensi extends Migration
{
    public function up()
    {
        Schema::table('absen', function (Blueprint $table) {
            // Tambah kolom status_kehadiran
            $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa'])
                  ->after('siswa_id')
                  ->nullable();

            // Tambah kolom waktu_absen
            $table->timestamp('waktu_absen')
                  ->after('status_kehadiran')
                  ->nullable();
        });
    }

    public function down()
    {
        Schema::table('absen', function (Blueprint $table) {
            $table->dropColumn(['status_kehadiran', 'waktu_absen']);
        });
    }
}
