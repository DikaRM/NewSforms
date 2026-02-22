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
            $table->integer("jadwal_id")->nullable();
            $table->string("nama_ujian");
            $table->date("durasi");
            $table->string("grade");
            $table->string("catatan");
            $table->string("status");
            
            $table->timestamps();
            
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            $table->foreign("jadwal_id")->references("id")->on("jadwal")->onDelete("cascade");
            $table->foreign("mapel")->references("id")->on("mapel")->onDelete("cascade");
            
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
