<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Siswa extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "siswa";
    protected $primaryKey = "id_siswa";
    public $incrementing = true;
    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
        'kelas_id'
    ];
    public function kelas()
{
 return $this->belongsTo(Kelas::class);
}

}
