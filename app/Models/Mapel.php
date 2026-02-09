<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Mapel extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "mapel";
    public $timestamps = false;
    protected $fillable = [
        'nama_mapel',
    ];
    public function guru()
    {
      return $this->HasMany(Guru::class);
    }


}
