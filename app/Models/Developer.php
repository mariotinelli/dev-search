<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    protected $fillable = [
        'login',
        'name',
        'avatar_url',
        'html_url',
        'email',
        'location',
        'bio',
        'followers',
        'repos',
        'stars',
        'score',
    ];

    protected $appends = ['is_favorite'];

    public function getIsFavoriteAttribute(): bool
    {
        return auth()->check() && $this->favoriteBy()->where('user_id', auth()->id())->exists();
    }

    public function favoriteBy(): HasMany
    {
        return $this->hasMany(FavoriteDeveloper::class);
    }
}
