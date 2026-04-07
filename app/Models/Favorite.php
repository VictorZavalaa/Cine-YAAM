<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    //Campos que puede modificar el usuario en la tabla favorite
    protected $table = 'favorite';

    protected $fillable = ['idUserF', 'idMovieF'];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUserF');
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'idMovieF');
    }
}
