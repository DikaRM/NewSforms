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
        Schema::create('ujian', function (Blueprint $table) {
            $table->id();
           $table->unsignedBigInteger('guru_id');
            $table->unsignedBigInteger('mapel');
           $table->foreignId('jadwal_id')->nullable()->constrained('jadwal')->cascadeOnDelete();
            $table->string("nama_ujian");
            $table->date("durasi");
            $table->string("grade");
            $table->string("catatan");
            $table->string("status");
            
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            
            $table->foreign("mapel")->references("id")->on("mapel")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('guru_id');
            $table->index('mapel');
            $table->index('jadwal_id');
            $table->index('nama_ujian');
            $table->index('durasi');
            $table->index('grade');
            $table->index('status');
            $table->index('created_at');
            
            // Composite indexes (untuk query yang sering pakai kombinasi)
            $table->index(['guru_id', 'status']);
            $table->index(['mapel', 'status']);
            $table->index(['status', 'durasi']);
            $table->index(['guru_id', 'durasi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian');
    }
};