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
            
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            
            $table->foreign("bank_id")->references("id")->on("bank")->onDelete("cascade");
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
