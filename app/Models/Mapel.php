<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    protected $table = "mapel";
    protected $fillable = [
        "nama_mapel",
    ];
    public function guru(){
        return $this->belongsToMany(Guru::class,"guru_mapel");
    }
}
