<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EventRegistration extends Model
{
    protected $fillable = [
        'event_id',
        'ticket_id',
        'user_id',
        'registration_code',
        'status',
        'qr_code',
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

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(
            EventTicket::class,
            'ticket_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
        );
    }

    public function checkins(): HasMany
    {
        return $this->hasMany(
            EventCheckin::class,
            'registration_id',
        )->orderBy('checked_in_at');
    }
}