<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Nilai extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "jadwal";
    protected $fillable = [
        'nilai',
        'ujian_id',
        'mapel_id',
        'siswa_id',
    ];
public function siswa()
{
 return $this->belongsTo(Siswa::class);
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
