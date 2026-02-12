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
            $table->string('guru_id');
            $table->string('mapel');
            $table->string('kelas_id');
            $table->string("nama_ujian");
            $table->date("waktu_mulai");
            $table->date("waktu_selesai");
            $table->date("durasi");
            $table->string("status");
            $table->timestamps();
            
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            $table->foreign("mapel")->references("id")->on("mapel")->onDelete("cascade");
            $table->foreign("kelas_id")->references("id")->on("kelas")->onDelete("cascade");
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
