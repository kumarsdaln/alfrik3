<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Continent extends Model
{
    public $incrementing = false;

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    public function countries(): HasMany
    {
        return $this->hasMany(
            Country::class,
            'continent_code',
            'code',
        );
    }
}