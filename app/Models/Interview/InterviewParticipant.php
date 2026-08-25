<?php

namespace App\Models\Interview;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class InterviewParticipant extends Model
{
    protected $fillable = ['interview_id','user_id','role'];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
