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
        Schema::create('berita', function (Blueprint $table) {
            $table->id();
            
            // ============ KARENA SISWA PAKAI id_siswa ============
            $table->unsignedBigInteger('siswa_id');
            $table->foreign('siswa_id')
                  ->references('id_siswa')
                  ->on('siswa')
                  ->onDelete('cascade');
            
            // Ujian dan pengawas menggunakan id standar
            $table->foreignId('ujian_id')
                  ->constrained('ujian')
                  ->onDelete('cascade')
                  ->index();
            
            $table->foreignId('pengawas_id')
                  ->constrained('pengawas')
                  ->onDelete('cascade')
                  ->index();
            
            // Kolom catatan
            $table->text('catatan')->nullable();
            
            $table->timestamps();
            
            // ============ INDEXING ============
            $table->index('siswa_id');
            $table->index('ujian_id');
            $table->index('pengawas_id');
            
            // Composite indexes
            $table->index(['siswa_id', 'ujian_id']);
            $table->index(['pengawas_id', 'ujian_id']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berita');
    }
};