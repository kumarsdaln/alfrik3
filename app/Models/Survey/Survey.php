<?php

namespace App\Models\Survey;

use App\Enums\Survey\SurveyStatus;
use App\Models\Research\Research;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

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
    use HasCategories, HasTags, HasMedia, HasSeo;


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

    #[Scope]
    public function published(Builder $query)
    {
        $query->where('status', SurveyStatus::Published);
    }

    #[Scope]
    public function featured(Builder $query)
    {
        $query->where('featured', true);
    }


    // Relationships
    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(SurveySection::class, 'survey_id', 'id')
            ->orderBy('position');
    }

    public function questions(): HasMany
    {
        return $this->hasMany(SurveyQuestion::class, 'survey_id', 'id')
            ->orderBy('position');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class);
    }
}
