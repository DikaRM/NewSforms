<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockSiswa extends Model
{
    protected $table = 'block_siswa';
    protected $fillable = [
        'siswa_id', 
        'ujian_id', 
        'violation_count', 
        'blocked_at', 
        'expires_at',
    ];
    
    protected $casts = [
        'blocked_at' => 'datetime',
        'expires_at' => 'datetime',
    ];
    
    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'siswa_id', 'id_siswa');
    }
    
    public function ujian()
    {
        return $this->belongsTo(Ujian::class);
    }
}