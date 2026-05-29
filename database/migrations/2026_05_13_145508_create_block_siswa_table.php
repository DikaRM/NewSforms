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
        Schema::create('block_siswa', function (Blueprint $table) {
    $table->id();
    $table->unsignedBigInteger('siswa_id');
    $table->unsignedBigInteger('ujian_id');
    $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
    $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
    $table->integer('violation_count')->default(0);
    $table->timestamp('blocked_at')->nullable();
    $table->timestamp('expires_at')->nullable();
    $table->timestamps();
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('block_siswa');
    }
};
