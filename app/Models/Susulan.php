<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Susulan extends Model
{
    protected $table ="susulan";
    protected $fillable = ["ujian_id","kelas_id","siswa_id","alasan"];
    public function siswa(){
        return $this->belongsTo(Siswa::class,'siswa_id','id_siswa');
    }
    public function ujian(){
        return $this->hasOne(Ujian::class);
    }
    public function kelas(){
        return $this->hasOne(Kelas::class);
    }
}
