<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class GuruMapel extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "guru_mapel";
    protected $fillable = [
      'mapel_id',
      'guru_id',
    ];


}
