<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'response_id',
    'question_id',
    'option_id',
    'answer_text',
    'answer_number',
    'answer_boolean',
    'answer_json',
])]
class SurveyAnswer extends Model
{
    use HasFactory;


    protected function casts(): array
    {
        return [
            'answer_number' => 'decimal:4',
            'answer_boolean' => 'boolean',
            'answer_json' => 'array',
        ];
    }

    public function response(): BelongsTo
    {
        return $this->belongsTo(
            SurveyResponse::class,
            'response_id'
        );
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(
            SurveyQuestion::class,
            'question_id'
        );
    }

    public function option(): BelongsTo
    {
        return $this->belongsTo(
            SurveyQuestionOption::class,
            'option_id'
        );
    }
}
