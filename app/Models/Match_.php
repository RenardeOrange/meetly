<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Match_ extends Model
{
    protected $table = 'matches';

    protected $fillable = [
        'user_1_id',
        'user_2_id',
        'score_compatibilite',
        'statut',
    ];

    public function user1(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_1_id');
    }

    public function user2(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_2_id');
    }

    public function chat(): HasOne
    {
        return $this->hasOne(Chat::class, 'match_id');
    }
}
