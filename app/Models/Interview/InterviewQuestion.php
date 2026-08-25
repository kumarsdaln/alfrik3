<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InterviewQuestion extends Model
{
    protected $fillable = [
        'interview_id','asked_by','question','order'
    ];

    public function answers() {
        return $this->hasMany(InterviewAnswer::class, 'question_id', 'id');
    }

    public function interviewer() {
        return $this->belongsTo(User::class, 'asked_by', 'id');
    }
}
