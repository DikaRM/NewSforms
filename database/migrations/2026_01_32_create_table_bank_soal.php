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
        Schema::create('bank', function (Blueprint $table) {
            $table->id();
            $table->string('soal');
            $table->string('guru_id');
            $table->string('mapel_id');
            
            $table->string('opsi_a')->nullable();
            $table->string('opsi_b')->nullable();
            $table->string('opsi_c')->nullable();
            $table->string('opsi_d')->nullable();
            $table->timestamp('jawaban_benar')->nullable();
            
            $table->timestamps();
            $table->foreign("mapel_id")->references("id")->on("mapel")->onDelete("cascade");
            
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank');
    }
};
