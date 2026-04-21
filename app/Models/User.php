<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;

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
        'type_connexion',
        'dark_mode',
        'langue',
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
            'dark_mode' => 'boolean',
            'type_connexion' => 'array',
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

    public function blocksInitiated(): HasMany
    {
        return $this->hasMany(UserBlock::class, 'blocker_id');
    }

    public function blockedByOthers(): HasMany
    {
        return $this->hasMany(UserBlock::class, 'blocked_id');
    }

    public function reportsFiled(): HasMany
    {
        return $this->hasMany(UserReport::class, 'reporter_id');
    }

    public function reportsReceived(): HasMany
    {
        return $this->hasMany(UserReport::class, 'reported_user_id');
    }

    public function blockedUserIds(): Collection
    {
        return $this->blocksInitiated()->pluck('blocked_id');
    }

    public function blockedByUserIds(): Collection
    {
        return $this->blockedByOthers()->pluck('blocker_id');
    }

    /** Jaccard-based interest match percentage (0-100) */
    public function matchScore(User $other): int
    {
        $mine  = $this->interets->pluck('id');
        $theirs = $other->interets->pluck('id');

        if ($mine->isEmpty() && $theirs->isEmpty()) return 0;

        $common = $mine->intersect($theirs)->count();
        $total  = $mine->merge($theirs)->unique()->count();

        return $total > 0 ? (int) round(($common / $total) * 100) : 0;
    }
}
