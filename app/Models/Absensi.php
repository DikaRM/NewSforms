<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
protected $table ="absen";
    protected $fillable = ["ujian_id","kelas_id","siswa_id","status_kehadiran","waktu_absen","created_at"];
    public function siswa(){
        return $this->belongsTo(Siswa::class,'siswa_id','id_siswa');
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class,'ujian_id','id');
    }
    public function kelas(){
        return $this->hasOne(Kelas::class);
    }
}
