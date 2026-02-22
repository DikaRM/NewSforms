<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Berita extends Model
{
    protected $table = "berita";
    protected $fillable = [
        "siswa_id",
        "ujian_id",
        "pengawas_id",
        "catatan",
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
    public function pengawas(){
        return $this->belongsTo(Pengawas::class);
    }
}
