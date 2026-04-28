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
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id();

            // ✅ PERBAIKAN 1: Gunakan unsignedBigInteger (Integer)
            // Karena ID di tabel ujian, siswa, dan banksoal biasanya Integer
            $table->unsignedBigInteger('ujian_id');
            $table->unsignedBigInteger('siswa_id'); 
            $table->unsignedBigInteger('bank_id');
            
            // Gunakan 'text' saja untuk jawaban agar bisa menampung jawaban panjang
            $table->text('jawaban')->nullable();
            $table->boolean("benar")->default(false);
            
            $table->timestamps();
            
            // --- Foreign Keys ---
            
            // Pastikan tabel 'siswa' memang bernama 'siswa' (biasanya 'siswas' jika pakai plural)
            $table->foreign("siswa_id")->references("id_siswa")->on("siswa")->onDelete("cascade");
            $table->foreign("ujian_id")->references("id")->on("ujian")->onDelete("cascade");
            
            // ✅ PERBAIKAN 2: Cek nama tabel referensi 'bank'
            // Jika tabel soal Anda bernama 'banksoals', ganti on('bank') menjadi on('banksoals')
            // Jika tabel Anda memang bernama 'bank', biarkan seperti ini.
            $table->foreign("bank_id")->references("id")->on("bank")->onDelete("cascade"); 
            
            // ============ INDEX ============
            
            // Unique Key (Wajib untuk Upsert) -> Ini SUDAH BENAR 👍
            $table->unique(['ujian_id', 'siswa_id', 'bank_id'], 'unique_jawaban_siswa');
            
            // Index Optimasi (Opsional tapi bagus)
            $table->index(['ujian_id', 'siswa_id']);
            $table->index(['bank_id', 'jawaban']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};