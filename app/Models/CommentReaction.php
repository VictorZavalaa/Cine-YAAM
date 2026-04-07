<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentReaction extends Model
{
    protected $table = 'comment_reaction';

    protected $fillable = ['idCommentR', 'idUserR', 'typeR'];

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'idCommentR');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUserR');
    }
}
