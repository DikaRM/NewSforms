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
        Schema::create('siswa', function (Blueprint $table) {
            $table->id("id_siswa");
            $table->string('user_id')->index();
            $table->string('nama')->index();
            $table->string("nisn")->unique()->index();
            $table->string("kelas_id")->index();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign("kelas_id")->references("id")->on("kelas")->onDelete("cascade");
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            
            // ============ INDEX OPTIMASI ============
            
            $table->index('created_at');
            $table->index(['kelas_id', 'nama']);
            $table->index(['kelas_id', 'created_at']);
            $table->index(['user_id', 'kelas_id']);
            $table->index(['nisn', 'kelas_id']);
            
            // Index untuk soft deletes (opsional)
            // $table->softDeletes()->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};