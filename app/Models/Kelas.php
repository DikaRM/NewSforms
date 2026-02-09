<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Kelas extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "kelas";
    protected $fillable = [
        'nama_kelas',
    ];
public function siswa()
{
 return $this->HasMany(Siswa::class);
}


}
