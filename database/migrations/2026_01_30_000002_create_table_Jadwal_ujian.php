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
            
            // Perbaikan: jam_mapel sebaiknya time atau string dengan format
            $table->time('jam_mapel')->index();  // atau tetap string jika format custom
            
            // Perbaikan: waktu_mulai dan waktu_selesai seharusnya datetime, bukan date
            $table->dateTime("waktu_mulai")->nullable()->index();
            $table->dateTime("waktu_selesai")->nullable()->index();
            
            // Kolom tanggal bisa diambil dari waktu_mulai, atau tetap dipertahankan
            $table->date('tanggal')->index();  // derived dari waktu_mulai
            
            $table->unsignedBigInteger('pengawas_id')->index();
            $table->unsignedBigInteger('ujian_id')->index();
            $table->unsignedBigInteger('kelas_id')->index();
            
            $table->timestamps();
            
            // Foreign keys
            $table->foreign("pengawas_id")->references("id")->on("pengawas")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            $table->foreign("kelas_id")->references("id")->on("kelas")->onDelete("cascade");
            
            // ============ INDEX OPTIMASI ============
            
            $table->index('created_at');
            $table->index(['tanggal', 'jam_mapel']);
            $table->index(['pengawas_id', 'tanggal']);
            $table->index(['kelas_id', 'tanggal']);
            $table->index(['ujian_id', 'tanggal']);
            $table->index(['pengawas_id', 'waktu_mulai', 'waktu_selesai']);
            $table->index(['kelas_id', 'waktu_mulai']);
            
            // Unique constraint untuk mencegah jadwal bentrok
            $table->unique(['kelas_id', 'tanggal', 'jam_mapel'], 'unique_jadwal_kelas');
            $table->unique(['pengawas_id', 'tanggal', 'jam_mapel'], 'unique_jadwal_pengawas');
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