<?php

namespace App\Models\Interview;

use App\Models\ContentParticipant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'question_id',
    'answered_by',
    'answer',
    'position',
])]
class InterviewAnswer extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(InterviewQuestion::class);
    }

    public function answerer(): BelongsTo
    {
        return $this->belongsTo(ContentParticipant::class, 'answered_by');
    }
}