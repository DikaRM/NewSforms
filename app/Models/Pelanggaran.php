<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pelanggaran extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "pelanggaran";
    protected $fillable = [
        'ujian_id',
        'siswa_id',
        'jenis_pelanggaran',
        'waktu',
    ];


}
