<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Pengawas extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "pengawas";
    protected $fillable = [
        'guru_id',
        'nama',
        'nip',
    ];


}
