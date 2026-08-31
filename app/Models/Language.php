<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'code',
    'name',
    'native',
    'rtl',
])]
class Language extends Model
{
    protected $casts = [
        'rtl' => 'boolean',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'user_languages',
            'language_id',
            'user_id',
        )->withTimestamps();
    }
}