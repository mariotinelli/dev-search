<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}
