<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'interview_id',
    'user_id',
    'role'
])]
class InterviewParticipant extends Model
{
    protected $fillable = [
        'interview_id',
        'user_id',
        'role',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            Interview::class,
            'interview_id',
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'user_id',
        );
    }
}
