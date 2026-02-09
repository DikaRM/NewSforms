<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Guru extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "guru";
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
    ];
    public function mapels(){
      return $this->belongsToMany(Mapel::class,"
      Guru_Mapel,
      guru_id,
      mapel_id,
      id
      ");
    }

}
