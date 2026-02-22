<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengawas extends Model
{
    protected $table = "pengawas";
    protected $fillable = [
        "user_id",
        "guru_id",
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function guru(){
        return $this->belongsTo(Guru::class);
    }
}
