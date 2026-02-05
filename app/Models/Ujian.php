<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Ujian extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "ujian";
    protected $fillable = [
        'mapel_id',
        'kelas_id',
        'guru_id',
        'nama_ujian',
        'waktu_mulai',
        'waktu_selesai',
        'durasi',
        'status',
        
    ];


}
