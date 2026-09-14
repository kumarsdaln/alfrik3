<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'question_id',
    'answered_by',
    'answer',
])]
class InterviewAnswer extends Model
{
    use HasFactory;

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            InterviewQuestion::class,
        );
    }

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'answered_by',
        );
    }
}
