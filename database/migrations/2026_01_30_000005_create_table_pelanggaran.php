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
        Schema::create('pelanggaran', function (Blueprint $table) {
            $table->id();
            $table->string('ujian_id');
            $table->string('siswa_id');
            $table->string('jenis_pelanggaran');
            $table->timestamps("waktu");
            
            // Foreign keys (dipertahankan)
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('ujian_id');
            $table->index('siswa_id');
            $table->index('jenis_pelanggaran');
            $table->index('waktu');  // karena timestamps pakai nama 'waktu'
            
            // Composite indexes (untuk query yang sering pakai kombinasi)
            // Query: WHERE ujian_id = ? AND siswa_id = ?
            $table->index(['ujian_id', 'siswa_id']);
            
            // Query: WHERE ujian_id = ? AND jenis_pelanggaran = ?
            $table->index(['ujian_id', 'jenis_pelanggaran']);
            
            // Query: WHERE siswa_id = ? AND jenis_pelanggaran = ?
            $table->index(['siswa_id', 'jenis_pelanggaran']);
            
            // Query: WHERE ujian_id = ? AND waktu BETWEEN ? AND ?
            $table->index(['ujian_id', 'waktu']);
            
            // Query: WHERE siswa_id = ? AND waktu BETWEEN ? AND ?
            $table->index(['siswa_id', 'waktu']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pelanggaran');
    }
};