<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


#[Fillable([
    'interview_id',
    'asked_by',
    'question',
    'order',
])]
class InterviewQuestion extends Model
{
    protected $casts = [
        'order' => 'integer',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            Interview::class,
            'interview_id',
        );
    }

    public function answers(): HasMany
    {
        return $this->hasMany(
            InterviewAnswer::class,
            'question_id',
        );
    }

    public function interviewer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'asked_by',
        );
    }
}
