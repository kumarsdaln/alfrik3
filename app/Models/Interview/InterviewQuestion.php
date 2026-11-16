<?php

namespace App\Models\Interview;

use App\Models\ContentParticipant;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'interview_id',
    'asked_by',
    'question',
    'position',
])]
class InterviewQuestion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function interview(): BelongsTo
    {
        return $this->belongsTo(Interview::class);
    }

    public function asker(): BelongsTo
    {
        return $this->belongsTo(ContentParticipant::class, 'asked_by');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(InterviewAnswer::class, 'question_id')
            ->orderBy('position');
    }
}