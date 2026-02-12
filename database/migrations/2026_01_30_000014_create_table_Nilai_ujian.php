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
        Schema::create('nilai', function (Blueprint $table) {
            $table->id();
            $table->string('nilai');
            $table->string('ujian_id');
            $table->string('mapel_id');
            $table->string('siswa_id');
            

            $table->timestamps();
            $table->foreign("mapel_id")->references("id")->on("mapel")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            
            
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
