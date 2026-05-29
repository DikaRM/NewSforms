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

    $table->foreignId('kelas_id')
        ->constrained('kelas')
        ->cascadeOnDelete();

    $table->foreignId('ujian_id')
        ->constrained('ujian')
        ->cascadeOnDelete();

    $table->foreignId('pengawas_id')
        ->constrained('pengawas')
        ->cascadeOnDelete();

    $table->text('catatan')->nullable();

    $table->timestamps();

    $table->index(['kelas_id', 'ujian_id']);
    $table->index(['pengawas_id', 'ujian_id']);
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