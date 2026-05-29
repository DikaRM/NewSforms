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
        Schema::create('kelas_ujian', function (Blueprint $table) {
            $table->id();
            
            // Ubah tipe data menjadi foreignId untuk kecepatan dan efisiensi
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
            $table->foreignId('ujian_id')->constrained('ujian')->onDelete('cascade');
            
            $table->timestamps();
            
            // ============ INDEXING STRATEGI ============
            
            // 1. Single column indexes (untuk query per tabel)
            // Query: WHERE kelas_id = ?
            $table->index('kelas_id');
            
            // Query: WHERE ujian_id = ?
            $table->index('ujian_id');
            
            // 2. Composite unique index (mencegah duplikasi data)
            // Mencegah kombinasi kelas_id dan ujian_id yang sama terdaftar dua kali
            $table->unique(['kelas_id', 'ujian_id']);
            
            // 3. Composite index untuk query yang melibatkan kedua kolom
            // Query: WHERE kelas_id = ? AND ujian_id = ?
            // (sebenarnya sudah tercakup oleh unique index di atas)
            // $table->index(['kelas_id', 'ujian_id']); // tidak perlu karena unique sudah mencakup
            
            // 4. Index untuk created_at jika sering filter berdasarkan waktu
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas_ujian');
    }
};