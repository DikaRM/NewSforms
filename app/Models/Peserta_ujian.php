<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Peserta_ujian extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "ujian_peserta";
    protected $fillable = [
        'siswa_id',
        'ujian_id',
        'status',
    ];


}
