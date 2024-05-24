<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Assistant extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'cpf',
        'user_id'
    ];

    protected $appends = ['situation'];

    public function getSituationAttribute(): string
    {
        return $this->deleted_at ? 'Inativo' : 'Ativo';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
