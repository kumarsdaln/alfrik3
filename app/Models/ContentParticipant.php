<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'user_id',
    'participant_role_id',
    'participantable_type',
    'participantable_id',
    'position',
    'is_primary',
    'contribution',
])]
class ContentParticipant extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'is_primary' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(
            ParticipantRole::class,
            'participant_role_id'
        );
    }

    public function participantable(): MorphTo
    {
        return $this->morphTo();
    }
}