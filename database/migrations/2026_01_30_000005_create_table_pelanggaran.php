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

    $table->foreignId('ujian_id')
        ->constrained('ujian')
        ->cascadeOnDelete();

    $table->foreignId('siswa_id')
        ->constrained('siswa', 'id_siswa')
        ->cascadeOnDelete();

    $table->string('jenis_pelanggaran');

    $table->timestamps();

    $table->index('ujian_id');
    $table->index('siswa_id');
    $table->index('jenis_pelanggaran');

    $table->index(['ujian_id', 'siswa_id']);
    $table->index(['ujian_id', 'jenis_pelanggaran']);
    $table->index(['siswa_id', 'jenis_pelanggaran']);
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