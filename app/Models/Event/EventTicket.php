<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'event_id',
    'name',
    'slug',
    'description',
    'price',
    'currency',
    'capacity',
    'sales_start',
    'sales_end',
    'is_visible',
    'is_active',
    'position',
])]
class EventTicket extends Model
{

    protected $casts = [
        'price' => 'decimal:2',
        'capacity' => 'integer',
        'sales_start' => 'datetime',
        'sales_end' => 'datetime',
        'is_visible' => 'boolean',
        'is_active' => 'boolean',
        'position' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function event(): BelongsTo
    {
        return $this->belongsTo(
            Event::class,
        );
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(
            EventRegistration::class,
            'ticket_id',
        );
    }
}