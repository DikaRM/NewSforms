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
        Schema::create('guru_mapel', function (Blueprint $table) {
            $table->id();
            $table->string('guru_id');
            $table->string('mapel_id');
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            $table->foreign("mapel_id")->references("id")->on("mapel")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('guru_id');
            $table->index('mapel_id');
            $table->index('created_at');
            
            // Composite unique index (mencegah duplikasi data)
            // Mencegah guru yang sama mengajar mapel yang sama terdaftar dua kali
            $table->unique(['guru_id', 'mapel_id'], 'unique_guru_mapel');
            
            // Composite index untuk query kombinasi
            $table->index(['guru_id', 'mapel_id']);
            
            // Index untuk query pencarian cepat
            // Contoh: mencari semua mapel yang diajar oleh guru tertentu
            // atau mencari semua guru yang mengajar mapel tertentu
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru_mapel');
    }
};