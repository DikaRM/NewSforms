<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = "jadwal";
    protected $fillable = [
        "jam_mapel",
        "tanggal",
        "pengawas_id",
        "ujian_id",
        "kelas_id",
        "waktu_mulai",
        "waktu_selesai",
        "untuk_susulan",
        "keterangan",
    ];
    public function pengawas(){
        return $this->belongsTo(Pengawas::class);
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
}
