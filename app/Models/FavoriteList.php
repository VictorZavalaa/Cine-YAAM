<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FavoriteList extends Model
{
    protected $table = 'favorite_list';

    protected $fillable = ['idUserL', 'nameL'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'idUserL');
    }

    public function items(): HasMany
    {
        return $this->hasMany(FavoriteListItem::class, 'idListLI');
    }
}
