<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class banksoal extends Model
{
    protected $table = "bank";
    protected $fillable = [
      "soal",
      "guru_id",
      "mapel_id",
      "opsi_a",
      "opsi_b",
      "opsi_c",
      "opsi_d",
      "jawaban_benar",
      ];
      public function guru()
    {
      return $this->belongsTo(Siswa::class);
    }
    
    public function mapel()
    {
      return $this->belongsTo(Mapel::class);
    }
    public function ujia()
    {
      return $this->belongsTo(Ujian::class);
    }
}
