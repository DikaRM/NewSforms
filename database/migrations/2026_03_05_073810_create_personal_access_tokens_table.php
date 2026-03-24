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
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            $table->morphs('tokenable'); // otomatis membuat tokenable_id dan tokenable_type dengan index
            $table->text('name');
            $table->string('token', 64)->unique(); // sudah unique dan index otomatis
            $table->text('abilities')->nullable();
            $table->timestamp('last_used_at')->nullable()->index(); // index untuk tracking
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
            
            // ============ TAMBAHAN INDEX OPTIMASI ============
            
            // Index untuk created_at dan updated_at
            $table->index('created_at');
            $table->index('updated_at');
            
            // Composite index untuk query token yang masih aktif
            $table->index(['tokenable_id', 'tokenable_type', 'expires_at']);
            
            // Index untuk mencari token yang sudah kadaluarsa
            $table->index(['expires_at', 'created_at']);
            
            // Index untuk last_used_at (token yang tidak pernah dipakai)
            $table->index(['last_used_at', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};