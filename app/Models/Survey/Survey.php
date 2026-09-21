<?php

namespace App\Models\Survey;

use App\Enums\Survey\SurveyStatus;
use App\Models\Category;
use App\Models\Media;
use App\Models\Research\Research;
use App\Models\SeoMetadata;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable([
    'research_id',
    'title',
    'slug',
    'description',
    'status',
    'anonymous',
    'multiple_responses',
    'featured',
    'starts_at',
    'ends_at',
    'response_count',
])]
class Survey extends Model
{
    use HasFactory;


    protected function casts(): array
    {
        return [
            'status' => SurveyStatus::class,

            'anonymous' => 'boolean',
            'multiple_responses' => 'boolean',
            'featured' => 'boolean',

            'starts_at' => 'datetime',
            'ends_at' => 'datetime',

            'response_count' => 'integer',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(SurveySection::class)
            ->orderBy('position');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class)
            ->orderBy('position');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable'
        );
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable'
        );
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(
            SeoMetadata::class,
            'seoable'
        );
    }
}
