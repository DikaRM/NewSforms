<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ujian extends Model
{
    protected $table = "ujian";
    protected $casts = [
    'deadline' => 'datetime', // ← biar otomatis jadi Carbon
];
    protected $fillable = [
        "mapel",
        "guru_id",
        "nama_ujian",
        "grade",
        "catatan",
        "status",
        "durasi",
        "jadwal_id",
        "mode",
        "deadline",
    ];


    protected $appends = [];

public function getStatusRealtimeAttribute()
{
    if (!$this->jadwal) return null;

    $now = now();
    $mulai = \Carbon\Carbon::parse($this->jadwal->waktu_mulai);
    $selesai = \Carbon\Carbon::parse($this->jadwal->waktu_selesai);

    if ($now < $mulai) return 'ready';
    if ($now <= $selesai) return 'ongoing';
    return 'done';
}
   public function soals()
{
    return $this->belongsToMany(
        banksoal::class,
        'ujian_soals',
        'ujian_id',
        'bank_id'
    );
}
    public function mapels(){
        return $this->belongsTo(Mapel::class,"mapel");
    }
    public function guru(){
        return $this->belongsTo(Guru::class);
    }
    public function kelas (){
      return $this->belongsToMany(Kelas::class,'kelas_ujian',"ujian_id","kelas_id");
    }
    public function jadwal()
    {
      return $this->hasOne(Jadwal::class);
    }
    public function jawabanSiswa()
{
    return $this->hasMany(Jawaban_siswa::class, 'ujian_id');
}
    public function peserta()
    {
      return $this->hasMany(Peserta_ujian::class);
    }
}
