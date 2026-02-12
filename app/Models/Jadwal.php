<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Jadwal extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "jadwal";
    protected $fillable = [
        'jam_mapel',
        'tanggal',
        'ujian_id',
        'mapel_id',
        'kelas_id',
    ];
public function kelas()
{
 return $this->belongsTo(Kelas::class);
}
public function ujian()
{
 return $this->belongsTo(Ujian::class);
}
public function mapel()
{
 return $this->belongsTo(Mapel::class);
}


}
