<?php

namespace App\Models\Survey;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class SurveyResponse extends Model
{
    public const UPDATED_AT = null;

    protected $fillable = ['survey_id', 'user_id', 'session_token', 'ip_address'];

    // Session token (hijack vector) and IP (PII) stay out of serialized output.
    protected $hidden = ['session_token', 'ip_address'];

    public function survey()
    {
        return $this->belongsTo(Survey::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function answers()
    {
        return $this->hasMany(SurveyAnswer::class, 'response_id');
    }
}
