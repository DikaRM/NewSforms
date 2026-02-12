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
            
            
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            
            $table->foreign("siswa_id")->references("id")->on("siswa")->onDelete("cascade");
            
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
