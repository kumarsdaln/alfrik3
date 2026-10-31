<?php

namespace App\Models\Interview;

use App\Enums\Interview\InterviewStatus;
use App\Enums\Interview\InterviewType;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'title',
    'slug',
    'description',
    'interview_type',
    'status',
    'published_at',
    'created_by',
    'views_count',
])]
class Interview extends Model
{
    use HasFactory;
    use HasCategories, HasTags, HasMedia, HasSeo, HasParticipants;

    protected function casts(): array
    {
        return [
            'interview_type' => InterviewType::class,
            'status' => InterviewStatus::class,
            'published_at' => 'datetime',
            'views_count' => 'integer',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
        );
    }

    public function questions(): HasMany
    {
        return $this->hasMany(
            InterviewQuestion::class,
        )->orderBy('position');
    }
}
