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
            $table->string('siswa_id');
            $table->string('ujian_id');
            $table->string('pengawas_id');
            $table->string('catatan');
            $table->timestamps();
            
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            
            $table->foreign("pengawas_id")->references("id")->on("pengawas")->onDelete("cascade");
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
