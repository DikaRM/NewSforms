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
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->string('ujian_id');
            $table->string('siswa_id');
            $table->string('bank_id');
            $table->string('jawaban')->nullable();
            $table->boolean("benar");
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("bank_id")->references("id")->on("bank")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('ujian_id');
            $table->index('siswa_id');
            $table->index('bank_id');
            $table->index('benar');
            $table->index('created_at');
            
            // Composite indexes (untuk query kombinasi)
            
            // Query: WHERE ujian_id = ? AND siswa_id = ? (semua jawaban siswa di ujian tertentu)
            $table->index(['ujian_id', 'siswa_id']);
            
            // Query: WHERE ujian_id = ? AND siswa_id = ? AND bank_id = ? (jawaban spesifik)
            $table->index(['ujian_id', 'siswa_id', 'bank_id']);
            
            // Query: WHERE ujian_id = ? AND benar = ? (rekap jawaban benar/salah per ujian)
            $table->index(['ujian_id', 'benar']);
            
            // Query: WHERE siswa_id = ? AND benar = ? (statistik kebenaran per siswa)
            $table->index(['siswa_id', 'benar']);
            
            // Query: WHERE ujian_id = ? AND bank_id = ? (analisis soal per ujian)
            $table->index(['ujian_id', 'bank_id']);
            
            // Query: WHERE siswa_id = ? AND bank_id = ? (riwayat siswa untuk soal tertentu)
            $table->index(['siswa_id', 'bank_id']);
            
            // Query untuk koreksi otomatis (berdasarkan bank_id dan jawaban)
            $table->index(['bank_id', 'jawaban']);
            
            // Composite unique untuk mencegah duplikasi jawaban
            $table->unique(['ujian_id', 'siswa_id', 'bank_id'], 'unique_jawaban_siswa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};