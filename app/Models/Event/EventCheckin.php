<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'registration_id',
    'checked_in_by',
    'checked_in_at',
    'type',
    'gate',
    'device',
    'successful',
    'notes',
])]
class EventCheckin extends Model
{

    protected $casts = [
        'checked_in_at' => 'datetime',
        'successful' => 'boolean',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function registration(): BelongsTo
    {
        return $this->belongsTo(
            EventRegistration::class,
            'registration_id',
        );
    }

    public function checkedInBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'checked_in_by',
        );
    }
}