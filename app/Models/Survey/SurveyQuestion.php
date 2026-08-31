<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'survey_id',
    'question',
    'type',
    'required',
    'position',
    'settings',
])]
class SurveyQuestion extends Model
{

    protected $casts = [
        'required' => 'boolean',
        'settings' => 'array',
    ];

    public const TYPES = [
        'single_choice',
        'multiple_choice',
        'text',
        'rating',
    ];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function options()
    {
        return $this->hasMany(
            SurveyOption::class,
            'question_id'
        )->orderBy('position');
    }

    public function answers()
    {
        return $this->hasMany(
            SurveyAnswer::class,
            'question_id'
        );
    }

    public function isChoice(): bool
    {
        return in_array(
            $this->type,
            ['single_choice', 'multiple_choice'],
            true
        );
    }
}