<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventCategory extends Model
{
    public $timestamps = false;

    protected $fillable = ['name', 'slug'];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_category_map', 'category_id', 'event_id');
    }
}
