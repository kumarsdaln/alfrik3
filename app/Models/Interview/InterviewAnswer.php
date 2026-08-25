<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InterviewAnswer extends Model
{
    protected $fillable = [
        'question_id','answered_by','answer'
    ];

    public function answeredBy() {
        return $this->belongsTo(User::class, 'answered_by');
    }
}
