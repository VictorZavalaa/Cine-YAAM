<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    //Campos que puede modificar el usuario en la tabla favorite
    protected $table = 'comment';

    protected $fillable = ['idUserC', 'idMovieC', 'bodyComment'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUserC');
    }

    public function movie(): BelongsTo
    {
        return $this->belongsTo(Movie::class, 'idMovieC');
    }

    public function reactions(): HasMany
    {
        return $this->hasMany(CommentReaction::class, 'idCommentR');
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(CommentNotification::class, 'idCommentN');
    }
}
