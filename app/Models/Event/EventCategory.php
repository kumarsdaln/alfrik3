<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'name',
    'slug',
])]
class EventCategory extends Model
{
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(
            Event::class,
            'event_category_map',
            'category_id',
            'event_id',
        );
    }
}