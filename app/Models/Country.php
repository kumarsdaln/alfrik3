<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'name', 
    'code', 
    'native', 
    'phone', 
    'continent_code', 
    'capital', 
    'currency', 
    'languages'
])]
class Country extends Model
{
    public $timestamps = false;

    public function continent(): BelongsTo
    {
        return $this->belongsTo(
            Continent::class,
            'continent_code',
            'code',
        );
    }

    public function users(): HasMany
    {
        return $this->hasMany(
            User::class,
            'country_id',
        );
    }
}
