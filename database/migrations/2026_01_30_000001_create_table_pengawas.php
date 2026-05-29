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
        Schema::create('pengawas', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('guru_id')->index();           // index untuk relasi ke guru
            $table->unsignedBigInteger('user_id')->index();           // index untuk relasi ke user
            $table->timestamps();
            
            // Foreign keys (dipertahankan)
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->foreign("guru_id")->references("id")->on("guru")->onDelete("cascade");
            
            // ============ TAMBAHAN INDEX OPTIMASI ============
            
            // Index untuk created_at (sorting/filter tanggal)
            $table->index('created_at');
            
            // Composite unique index (mencegah guru menjadi pengawas dua kali)
            $table->unique(['guru_id', 'user_id'], 'unique_pengawas');
            
            // Composite index untuk query kombinasi
            $table->index(['guru_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
            
            // Index untuk soft deletes (opsional)
            // $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengawas');
    }
};