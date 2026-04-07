<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chat extends Model
{
    protected $fillable = [
        'match_id',
        'request_statut',
    ];

    public function match(): BelongsTo
    {
        return $this->belongsTo(Match_::class, 'match_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }
}
