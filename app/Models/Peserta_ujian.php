<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Peserta_ujian extends Model
{
    protected $table = "ujian_peserta";
    protected $fillable = [
        "siswa_id",
        "ujian_id",
        "nilai",
        "status",
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class,"siswa_id","id_siswa");
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
}
