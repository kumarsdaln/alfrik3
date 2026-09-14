<?php

namespace App\Models\Interview;

use App\Enums\Interview\InterviewParticipantRole;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'interview_id',
    'user_id',
    'role'
])]
class InterviewParticipant extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'role' => InterviewParticipantRole::class,
        ];
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            Interview::class,
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
        );
    }
}
