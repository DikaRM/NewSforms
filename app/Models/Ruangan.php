<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ruangan extends Model
{
     protected $table = "ruangans";
    protected $fillable = [
        "nama_ruang",
        "kode",
        "kode_expired_at",
    ];
    public function kelas(){
        return $this->belongsTo(Kelas::class);
    }
    
}
