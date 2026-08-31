<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'response_id',
    'question_id',
    'option_id',
    'value_text',
])]
class SurveyAnswer extends Model
{
    public $timestamps = false;

    public function response()
    {
        return $this->belongsTo(
            SurveyResponse::class,
            'response_id'
        );
    }

    public function question()
    {
        return $this->belongsTo(
            SurveyQuestion::class,
            'question_id'
        );
    }

    public function option()
    {
        return $this->belongsTo(
            SurveyOption::class,
            'option_id'
        );
    }
}