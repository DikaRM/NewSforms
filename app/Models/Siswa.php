<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class Siswa extends Authenticatable
{
    use HasApiTokens, HasFactory;

    use HasFactory;


    protected $table = 'siswa';


    protected $primaryKey = 'id_siswa';

    public $incrementing = true;


    protected $keyType = 'int';

    /**
     * Field yang boleh diisi (mass assignment)
     */
    protected $fillable = [
        'user_id',
        'nama',
        'nisn',
        'kelas_id',
        'status',
        'username',
        'nomor_absen',
        'jenis_kelamin'
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Relasi ke tabel kelas
     */
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }
    public function pelanggaran()
{
    return $this->hasMany(Pelanggaran::class, 'siswa_id', 'id_siswa');
 
}
 public function peserta_ujian()
    {
        return $this->hasMany(Peserta_ujian::class, 'siswa_id');
    }
public function absensi(){
return $this->hasMany(Absensi::class,'siswa_id','id_siswa');
}

    /**
     * Accessor untuk format nama
     */
    public function getNamaAttribute($value)
    {
        return ucwords($value);
    }

    /**
     * Mutator untuk NISN (contoh)
     */
    public function setNisnAttribute($value)
    {
        $this->attributes['nisn'] = strtoupper($value);
    }
}