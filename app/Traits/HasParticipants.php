<?php

namespace App\Traits;

use App\Models\ContentParticipant;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasParticipants
{
    public function participants(): MorphMany
    {
        return $this->morphMany(
            ContentParticipant::class,
            'participantable'
        )->orderBy('position');
    }
}