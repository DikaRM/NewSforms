<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $table = "guru";
    protected $fillable = [
        "user_id",
        "nama",
        "nip",
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function mapel()
    {
      return $this->belongsToMany(Mapel::class,"guru_mapel");
    }
    public function pengawas(){
        return $this->hasMany(Pengawas::class);
    }
}
