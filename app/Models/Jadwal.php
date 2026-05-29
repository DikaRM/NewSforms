<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    protected $table = "jadwal";
   protected $casts = [
    'untuk_susulan' => 'boolean',
    'kelas_id' => 'integer',
];
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
    // Ubah scope Anda
public function scopeSusulan($q)
{
    return $q->where('untuk_susulan', true);  // ✅ Pakai boolean true
    // ATAU
    // return $q->where('untuk_susulan', DB::raw('TRUE'));
}
    public function pengawas(){
        return $this->belongsTo(Pengawas::class);
    }
    public function ujian(){
        return $this->BelongsTo(Ujian::class);
    }
    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
    // Di dalam model Jadwal
public function peserta()
{
    return $this->hasMany(Peserta_ujian::class);
}
}
