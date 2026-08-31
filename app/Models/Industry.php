<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
])]
class Industry extends Model
{

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'industry_user',
            'industry_id',
            'user_id',
        )->withTimestamps();
    }
}