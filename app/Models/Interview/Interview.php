<?php

namespace App\Models\Interview;

use App\Enums\Interview\InterviewStatus;
use App\Enums\Interview\InterviewType;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'description',
    'introduction',
    'conclusion',
    'interview_type',
    'status',
    'published_at',
])]
class Interview extends Model
{
    use HasFactory;
    use HasCategories;
    use HasTags;
    use HasMedia;
    use HasSeo;
    use HasParticipants;

    protected function casts(): array
    {
        return [
            'interview_type' => InterviewType::class,
            'status' => InterviewStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            InterviewQuestion::class,
        )->orderBy('position');
    }
}