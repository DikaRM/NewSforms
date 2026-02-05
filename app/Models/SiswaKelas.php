<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class SiswaKelas extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "siswa_kelas";
    protected $fillable = [
      'siswa_id',
      'kelas_id',
    ];


}
