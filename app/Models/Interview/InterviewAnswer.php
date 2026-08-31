<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'question_id',
    'answered_by',
    'answer',
])]
class InterviewAnswer extends Model
{
    public function question(): BelongsTo
    {
        return $this->belongsTo(
            InterviewQuestion::class,
            'question_id',
        );
    }

    public function answeredBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'answered_by',
        );
    }
}
