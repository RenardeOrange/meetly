<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'email',
        'password',
        'nom',
        'prenom',
        'bio',
        'avatar_url',
        'role',
        'visibilite',
        'blacklisted',
        'position',
        'numero_programme',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'blacklisted' => 'boolean',
        ];
    }

    public function interets(): BelongsToMany
    {
        return $this->belongsToMany(Interet::class, 'interet_user');
    }

    public function matchesAsUser1(): HasMany
    {
        return $this->hasMany(Match_::class, 'user_1_id');
    }

    public function matchesAsUser2(): HasMany
    {
        return $this->hasMany(Match_::class, 'user_2_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }
}
