<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable([
    'event_id',
    'title',
    'description',
    'start_time',
    'end_time',
    'location',
    'meeting_url',
    'position',
])]
class EventSession extends Model
{

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
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

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(
            User::class,
            'session_speakers',
            'session_id',
            'user_id',
        )->withPivot('position')
            ->orderBy('position');
    }
}