<?php

namespace App\Models\Interview;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'interview_id',
    'media_type',
    'source_type',
    'file_url',
    'embed_url',
    'duration',
    'thumbnail',
])]
class InterviewMedia extends Model
{
    public $timestamps = false;

    protected $casts = [
        'duration' => 'integer',
    ];

    public function interview(): BelongsTo
    {
        return $this->belongsTo(
            Interview::class,
            'interview_id',
        );
    }
}
