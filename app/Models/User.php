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

    /**
     * Weighted interest score:
     * - exact shared interests count fully
     * - unmatched interests in the same category count partially
     * - shared dominant categories earn a bonus
     */
    public function matchScore(User $other): float
    {
        $mine = $this->interets;
        $theirs = $other->interets;

        if ($mine->isEmpty() && $theirs->isEmpty()) {
            return 0.0;
        }

        $exactMatchIds = $mine->pluck('id')->intersect($theirs->pluck('id'));
        $exactMatches = $exactMatchIds->count();

        $mineRemainder = $mine->reject(fn ($interet) => $exactMatchIds->contains($interet->id));
        $theirRemainder = $theirs->reject(fn ($interet) => $exactMatchIds->contains($interet->id));

        $similarMatches = 0.0;
        $myCategoryCounts = $mineRemainder
            ->filter(fn ($interet) => filled($interet->categorie))
            ->countBy('categorie');
        $theirCategoryCounts = $theirRemainder
            ->filter(fn ($interet) => filled($interet->categorie))
            ->countBy('categorie');

        foreach ($myCategoryCounts as $category => $count) {
            $similarMatches += min($count, $theirCategoryCounts->get($category, 0)) * 0.35;
        }

        $dominantBonus = $this->sharesDominantInterestCategoryWith($other) ? 1.0 : 0.0;

        return round($exactMatches + $similarMatches + $dominantBonus, 1);
    }

    protected function sharesDominantInterestCategoryWith(User $other): bool
    {
        $myDominantCategories = $this->dominantInterestCategories();
        $theirDominantCategories = $other->dominantInterestCategories();

        if ($myDominantCategories->isEmpty() || $theirDominantCategories->isEmpty()) {
            return false;
        }

        return $myDominantCategories->intersect($theirDominantCategories)->isNotEmpty();
    }

    protected function dominantInterestCategories(): Collection
    {
        $categoryCounts = $this->interets
            ->filter(fn ($interet) => filled($interet->categorie))
            ->countBy('categorie');

        if ($categoryCounts->isEmpty()) {
            return collect();
        }

        $topCount = $categoryCounts->max();

        return $categoryCounts
            ->filter(fn ($count) => $count === $topCount)
            ->keys()
            ->values();
    }
}
