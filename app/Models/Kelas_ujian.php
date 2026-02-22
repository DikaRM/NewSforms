<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas_ujian extends Model
{
    protected $table = "kelas_ujian";
    protected $fillable = [
        "kelas_id",
        "ujian_id",
    ];
    
}
