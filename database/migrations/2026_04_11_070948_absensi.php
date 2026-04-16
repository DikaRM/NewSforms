<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('absen', function (Blueprint $table) {
            $table->id();
            $table->integer('ujian_id')->index();
            $table->integer('siswa_id')->index();
            $table->integer("kelas_id")->index();
            $table->enum('status_kehadiran', ['hadir', 'sakit', 'izin', 'alfa'])
              ->nullable();

            // Tambah kolom waktu_absen
            $table->timestamp('waktu_absen')->nullable();
        
            $table->timestamps();

            // Foreign keys
            $table->foreign("kelas_id")->references("id")->on("kelas")->onDelete("cascade");
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");

            // ============ INDEX OPTIMASI ============

            $table->index('created_at');
            $table->index(['kelas_id', 'ujian_id']);
            $table->index(['kelas_id', 'created_at']);
            $table->index(['siswa_id', 'kelas_id']);

            // Index untuk soft deletes (opsional)
            // $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absen');
    }
};
