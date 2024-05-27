<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
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

    public function email(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $value ?? 'E-mail não informado',
        );
    }

    public function favoriteBy(): HasMany
    {
        return $this->hasMany(FavoriteDeveloper::class);
    }
}
