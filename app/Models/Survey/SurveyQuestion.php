<?php

namespace App\Models\Survey;

use App\Enums\Survey\SurveyQuestionType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'survey_id',
    'section_id',
    'question',
    'description',
    'type',
    'category',
    'required',
    'position',
    'settings',
])]
class SurveyQuestion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => SurveyQuestionType::class,
            'required' => 'boolean',
            'position' => 'integer',
            'settings' => 'array',
        ];
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(SurveySection::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(SurveyQuestionOption::class)
            ->orderBy('position');
    }

    public function answers(): HasMany
    {
        return $this->hasMany(SurveyAnswer::class);
    }
}
