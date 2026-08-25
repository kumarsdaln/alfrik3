<?php

namespace App\Models\Survey;

use Illuminate\Database\Eloquent\Model;

class SurveyOption extends Model
{
    public $timestamps = false;

    protected $fillable = ['question_id', 'label', 'position'];

    public function question()
    {
        return $this->belongsTo(SurveyQuestion::class, 'question_id');
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'option_id');
    }
}
