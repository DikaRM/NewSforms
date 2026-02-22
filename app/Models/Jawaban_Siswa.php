<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban_Siswa extends Model
{
    protected $table = "jawaban_siswa";
    protected $fillable = [
        "siswa_id",
        "bank_id",
        "ujian_id",
        "jawaban",
        "benar",
        
    ];
    public function siswa(){
        return $this->belongsTo(Siswa::class);
    }
    public function ujian(){
        return $this->belongsTo(Ujian::class);
    }
    public function bank(){
        return $this->belongsTo(banksoal::class);
    }
}
