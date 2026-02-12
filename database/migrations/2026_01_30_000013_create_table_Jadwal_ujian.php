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
        Schema::create('jadwal', function (Blueprint $table) {
            $table->id();
            $table->string('jam_mapel');
            $table->date('tanggal');
            
            $table->string('mapel_id');
            $table->string('ujian_id');
            $table->string('kelas_id');
            
            $table->timestamps();
            $table->foreign("mapel_id")->references("id")->on("mapel")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("kelas_id")->references("id")->on("kelas")->onDelete("cascade");
            
            
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal');
    }
};
