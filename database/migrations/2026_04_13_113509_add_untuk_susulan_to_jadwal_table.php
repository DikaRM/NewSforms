<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->boolean('untuk_susulan')->default(false);
            $table->text('keterangan')->nullable();
        });
    }

    public function down()
    {
        Schema::table('jadwal', function (Blueprint $table) {
            $table->dropColumn(['untuk_susulan', 'keterangan']);
        });
    }
};
