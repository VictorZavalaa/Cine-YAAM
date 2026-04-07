<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    //Campos que puede modificar el usuario en la tabla movie
    protected $table = 'movie';

    protected $fillable = ['sourceM', 'external_id', 'title', 'overview', 'releaseDate', 'posterPath'];

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class, 'idMovieF');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'idMovieC');
    }

    public function favoriteListItems(): HasMany
    {
        return $this->hasMany(FavoriteListItem::class, 'idMovieLI');
    }
}
