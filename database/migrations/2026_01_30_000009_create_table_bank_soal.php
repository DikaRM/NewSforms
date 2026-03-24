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
        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->string('soal');
            $table->string("gambar")->nullable();
            $table->string('guru_id')->index();           // index untuk pencarian by guru
            $table->string('mapel_id')->index();          // index untuk pencarian by mapel
            
            // Opsi jawaban
            $table->string('opsi_a')->nullable();
            $table->string('opsi_b')->nullable();
            $table->string('opsi_c')->nullable();
            $table->string('opsi_d')->nullable();
            $table->string('jawaban_benar')->nullable();
            
            // Tipe soal (pilihan_ganda, essay, dll)
            $table->string('tipe')->index();              // index untuk filter tipe soal
            
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("mapel_id")->references("id")->on("mapel")->onDelete("cascade");
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX OPTIMASI ============
            
            // Single column indexes (yang belum ditambahkan)
            $table->index('soal');                        // untuk pencarian berdasarkan soal
            $table->index('created_at');                  // untuk sorting/filter tanggal
            
            // Composite indexes (untuk query kombinasi)
            
            // Query: WHERE guru_id = ? AND mapel_id = ? (soal yang dibuat guru untuk mapel tertentu)
            $table->index(['guru_id', 'mapel_id']);
            
            // Query: WHERE mapel_id = ? AND tipe = ? (soal per mapel berdasarkan tipe)
            $table->index(['mapel_id', 'tipe']);
            
            // Query: WHERE guru_id = ? AND tipe = ? (soal per guru berdasarkan tipe)
            $table->index(['guru_id', 'tipe']);
            
            // Query: WHERE mapel_id = ? AND created_at BETWEEN ? AND ? (soal baru per mapel)
            $table->index(['mapel_id', 'created_at']);
            

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};