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
    public function guru()
    {
      return $this->belongsToMany(Siswa::class);
    }
    public function mapel()
    {
      return $this->belongsTo(Mapel::class);
    }


}
