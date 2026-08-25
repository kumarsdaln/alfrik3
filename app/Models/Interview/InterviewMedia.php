<?php

namespace App\Models\Interview;

use Illuminate\Database\Eloquent\Model;

class InterviewMedia extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'interview_id',
        'media_type',
        'source_type',
        'file_url',
        'embed_url',
        'duration',
        'thumbnail'
    ];
}
