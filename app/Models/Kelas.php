<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $table = "kelas";
    protected $fillable = [
        "nama_kelas",
        "ruangan_id",
    ];
    public function ujian(){
        return $this->belongsToMany(Ujian::class,"kelas_ujian","kelas_id","ujian_id");
    }
    public function ruangan(){
        return $this->belongsTo(Ruangan::class);
    }
    public function siswa()
    {
      return $this->hasMany(Siswa::class);
    }
}
