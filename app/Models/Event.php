<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    protected $fillable = [
        'creator_id',
        'group_id',
        'titre',
        'description',
        'date_evenement',
        'heure_debut',
        'lieu',
        'max_participants',
        'prix',
        'type_acces',
        'statut',
    ];

    protected $casts = [
        'date_evenement'  => 'date',
        'max_participants' => 'integer',
        'prix'            => 'decimal:2',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function participants(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_participants')
                    ->withPivot('statut')
                    ->withTimestamps();
    }

    public function confirmedParticipants(): BelongsToMany
    {
        return $this->participants()->wherePivot('statut', 'confirme');
    }

    public function pendingParticipants(): BelongsToMany
    {
        return $this->participants()->wherePivot('statut', 'en_attente');
    }

    public function isFull(): bool
    {
        if ($this->max_participants === null) {
            return false;
        }
        return $this->confirmedParticipants()->count() >= $this->max_participants;
    }
}
