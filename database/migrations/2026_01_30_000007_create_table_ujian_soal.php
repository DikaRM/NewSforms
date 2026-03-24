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
        Schema::create('ujian_soals', function (Blueprint $table) {
            $table->id();
            $table->string('ujian_id');
            $table->string('bank_id');
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("bank_id")->references("id")->on("bank")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX (OPTIMASI) ============
            
            // Single column indexes
            $table->index('ujian_id');
            $table->index('bank_id');
            $table->index('created_at');
            
            // Composite unique index (mencegah duplikasi soal di ujian yang sama)
            $table->unique(['ujian_id', 'bank_id'], 'unique_soal_ujian');
            
            // Composite index untuk query kombinasi
            $table->index(['ujian_id', 'bank_id']);
            
            // Index untuk urutan soal (jika perlu menambahkan kolom urutan nanti)
            // $table->integer('urutan')->nullable()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ujian_soals');
    }
};