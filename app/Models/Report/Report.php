<?php

namespace App\Models\Report;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Models\Category;
use App\Models\Media;
use App\Models\Research\Research;
use App\Models\Tag;
use App\Models\User;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Casts\Attribute;
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
    'subtitle',
    'description',
    'summary',
    'type',
    'status',
    'author_id',
    'featured',
    'published_at',
    'report_date',
])]
class Report extends Model
{
    use HasFactory;


    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'status' => ReportStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
            'report_date' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ReportSection::class)
            ->orderBy('position');
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
