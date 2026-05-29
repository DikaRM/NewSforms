<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class banksoal extends Model
{
    protected $table = "bank";
    protected $fillable = [
        "soal",
        "gambar",
        "guru_id",
        "mapel_id",
        "opsi_a",
        "opsi_b",
        "opsi_c",
        "opsi_d",
        "jawaban_benar",
        "tipe",
        "opsi_e",
        'media_url',
        'media_file'
    ];
    public function mapel(){
        return $this->belongsTo(Mapel::class);
    }
    public function guru(){
        return $this->belongsTo(Guru::class);
    }
     public function ujian()
{
    return $this->belongsToMany(
        Ujian::class,
        'ujian_soals',
        'bank_id',
        'ujian_id'
    );
}
}

