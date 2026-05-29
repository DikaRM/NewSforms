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
        Schema::create('ujian_peserta', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger("pelanggaran_id")->nullable();
            $table->string('nilai');
            $table->string('status');
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("pelanggaran_id")->references("id")->on("pelanggaran")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('siswa_id');
            $table->index('ujian_id');
            $table->index('pelanggaran_id');
            $table->index('nilai');
            $table->index('status');
            $table->index('created_at');
            
            // Composite indexes (untuk query yang sering pakai kombinasi)
            
            // Query: WHERE siswa_id = ? AND ujian_id = ? (mencari peserta di ujian tertentu)
            $table->index(['siswa_id', 'ujian_id']);
            
            // Query: WHERE ujian_id = ? AND status = ? (mencari peserta berdasarkan status di ujian)
            $table->index(['ujian_id', 'status']);
            
            // Query: WHERE siswa_id = ? AND status = ? (riwayat peserta berdasarkan status)
            $table->index(['siswa_id', 'status']);
            
            // Query: WHERE ujian_id = ? AND nilai >= ? (filter nilai minimal)
            $table->index(['ujian_id', 'nilai']);
            
            // Query: WHERE ujian_id = ? AND pelanggaran_id IS NOT NULL (peserta dengan pelanggaran)
            $table->index(['ujian_id', 'pelanggaran_id']);
            
            // Query untuk laporan/laporan (group by status per ujian)
            $table->index(['ujian_id', 'status', 'created_at']);
            
            // Query untuk analisis nilai per siswa
            $table->index(['siswa_id', 'nilai']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_peserta');
    }
};