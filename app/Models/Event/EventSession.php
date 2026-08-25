<?php

namespace App\Models\Event;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EventSession extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'event_id', 'title', 'description', 'start_time', 'end_time', 'meeting_url', 'location',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function speakers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'session_speakers', 'session_id', 'user_id');
    }
}
