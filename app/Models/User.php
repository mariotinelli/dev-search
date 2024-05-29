<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, HasOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'role_id',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $appends = ['situation'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'role_id'           => RoleEnum::class,
        ];
    }

    public function getSituationAttribute(): string
    {
        return $this->deleted_at ? 'Inativo' : 'Ativo';
    }

    public function hasRole(string $role): bool
    {
        return $this->role_id === RoleEnum::fromValue($role);
    }

    public function isAdmin(): bool
    {
        return $this->role_id === RoleEnum::ADMIN;
    }

    public function isCto(): bool
    {
        return $this->role_id === RoleEnum::CTO;
    }

    public function isAssistant(): bool
    {
        return $this->role_id === RoleEnum::ASSISTANT;
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function assistant(): HasOne
    {
        return $this->hasOne(Assistant::class);
    }

    public function favoriteDevelopers(): HasMany
    {
        return $this->hasMany(FavoriteDeveloper::class);
    }
}
