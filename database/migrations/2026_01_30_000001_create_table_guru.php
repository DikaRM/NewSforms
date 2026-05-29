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
        Schema::create('guru', function (Blueprint $table) {
            $table->id();
            $table->string('user_id')->index();           // index untuk relasi ke user
            $table->string('nama')->index();              // index untuk pencarian by nama
            $table->string("nip")->unique()->index();     // unique + index untuk NIP
            $table->timestamps();
            
            // ============ TAMBAHAN INDEX OPTIMASI ============
            
            // Index untuk created_at (sorting/filter tanggal)
            $table->index('created_at');
            
            // Composite index untuk pencarian kombinasi
            $table->index(['nama', 'nip']);               // untuk pencarian by nama dan NIP
            
            // Foreign key ke tabel users (jika diperlukan)
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('guru');
    }
};