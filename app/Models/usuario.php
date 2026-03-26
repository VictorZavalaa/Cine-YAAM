<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class usuario extends Model
{
    //Campos que puede modificar el usuario
    protected $table = 'usuario';

    protected $fillable = ['nombre', 'email', 'password'];
}
