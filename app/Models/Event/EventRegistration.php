<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventRegistration extends Model
{
    // Table has created_at but no updated_at.
    const UPDATED_AT = null;

    protected $fillable = ['event_id', 'user_id', 'ticket_id', 'status', 'qr_code', 'check_in_time'];

    protected $casts = ['check_in_time' => 'datetime'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
