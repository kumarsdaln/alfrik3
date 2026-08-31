<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'question_id',
    'label',
    'position',
])]
class SurveyOption extends Model
{
    public $timestamps = false;

    public function question()
    {
        return $this->belongsTo(
            SurveyQuestion::class,
            'question_id'
        );
    }

    public function answers()
    {
        return $this->hasMany(
            SurveyAnswer::class,
            'option_id'
        );
    }
}