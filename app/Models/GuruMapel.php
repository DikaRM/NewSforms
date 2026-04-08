<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuruMapel extends Model
{
    protected $table = "guru_mapel";
    protected $fillable = [
        "guru_id",
        "mapel_id",
      
    ];
    public function mapel(){
    return $this->belongsTo(Mapel::class);
    }
    public function guru(){
    return $this->belongsTo(Guru::class);
    }
    
}
