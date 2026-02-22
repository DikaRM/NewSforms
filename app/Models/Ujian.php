<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = "ujian";
    protected $fillable = [
        "mapel",
        "guru_id",
        "nama_ujian",
        "grade",
        "catatan",
        "status",
        "durasi",
        "jadwal_id"
    ];
    public function mapels(){
        return $this->belongsTo(Mapel::class,"mapel");
    }
    public function guru(){
        return $this->belongsTo(Guru::class);
    }
    public function kelas (){
      return $this->belongsToMany(Kelas::class,'kelas_ujian',"kelas_id","ujian_id");
    }
    public function jadwal()
    {
      return $this->belongsTo(Jadwal::class);
    }
}
