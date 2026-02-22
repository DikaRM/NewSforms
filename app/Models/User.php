<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
class User extends Authenticatable  
{
    protected $table = "users";
    protected $fillable =[
      "nama",
      "password",
      "role",
      ];
}
