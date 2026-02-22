<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggaran extends Model
{
    protected $table = "pelanggaran";
    protected $fillable = [
        "siswa_id",
        "ujian_id",
        "jenis_pelanggaran",
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
}
