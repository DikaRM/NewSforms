<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();

            // Gunakan foreignId untuk konsistensi
            $table->foreignId('ujian_id')->constrained()->cascadeOnDelete();
            $table->foreignId('siswa_id')->constrained('siswa', 'id_siswa')->cascadeOnDelete();
            $table->foreignId('kelas_id')->constrained()->cascadeOnDelete();

            $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa'])->nullable();
            $table->timestamp('waktu_absen')->nullable();
            $table->timestamps();
            $table->softDeletes();  // Aktifkan soft deletes

            // INDEX OPTIMAL (hapus yang redundant)
            $table->index(['ujian_id', 'status_kehadiran']);  // Cepat filter status per ujian
            $table->index(['kelas_id', 'ujian_id']);         // Untuk laporan per kelas
            $table->index(['kelas_id', 'created_at']);       // Untuk filter waktu per kelas
            $table->index(['siswa_id', 'ujian_id']);         // Cek absen siswa per ujian
            $table->index('waktu_absen');                    // Filter by waktu
            $table->index('deleted_at');                     // Untuk soft delete query
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};
