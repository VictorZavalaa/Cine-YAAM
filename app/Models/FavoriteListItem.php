<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FavoriteListItem extends Model
{
    protected $table = 'favorite_list_item';

    protected $fillable = ['idListLI', 'idMovieLI'];

    public function list(): BelongsTo
    {
        return $this->belongsTo(FavoriteList::class, 'idListLI');
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'idMovieLI');
    }
}
