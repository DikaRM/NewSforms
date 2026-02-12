<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Jawaban_siswa extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "jawaban_siswa";
    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'bank_id',
        'jawaban',
        'benar',
    ];
    public function ujian()
    {
      return $this->belongsTo(Ujian::class);
    }
    public function bank()
    {
      return $this->belongsTo(banksoal::class);
    }

}
