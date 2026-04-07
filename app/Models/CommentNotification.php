<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CommentNotification extends Model
{
    protected $table = 'comment_notification';

    protected $fillable = [
        'idUserN',
        'idFromUserN',
        'idCommentN',
        'typeN',
        'messageN',
        'isReadN',
    ];

    protected $casts = [
        'isReadN' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUserN');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idFromUserN');
    }

    public function comment(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'idCommentN');
    }
}
