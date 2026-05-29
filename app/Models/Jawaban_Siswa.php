<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Jawaban_Siswa extends Model
{
    protected $table = "jawaban_siswa";
    protected $casts = [
    'benar' => 'integer',
    // casts lain...
];
    protected $fillable = [
        "siswa_id",
        "bank_id",
        "ujian_id",
        "jawaban",
        "benar",
        "file_jawaban",
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
