<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian_soals extends Model
{
    protected $table = "ujian_soals";
    protected $fillable = [
        "ujian_id",
        "bank_id",
    ];
    
}
