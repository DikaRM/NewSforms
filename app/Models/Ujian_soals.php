<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Ujian_soals extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $table = "Ujian_soals";
    protected $fillable = [
        'ujian_id',
        'bank_id',
    ];


}
