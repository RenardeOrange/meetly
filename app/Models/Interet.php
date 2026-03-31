<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Interet extends Model
{
    protected $table = 'interets';

    protected $fillable = [
        'nom',
        'categorie',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'interet_user');
    }
}
