<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventReview extends Model
{
    // Table has created_at but no updated_at.
    const UPDATED_AT = null;

    protected $fillable = ['event_id', 'user_id', 'rating', 'review'];

    protected $casts = ['rating' => 'integer'];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
