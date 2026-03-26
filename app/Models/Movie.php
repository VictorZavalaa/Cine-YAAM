<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //Campos que puede modificar el usuario en la tabla movie
    protected $table = 'movie';

    protected $fillable = ['sourceM', 'external_id', 'title', 'overview', 'releaseDate', 'posterPath'];
}
