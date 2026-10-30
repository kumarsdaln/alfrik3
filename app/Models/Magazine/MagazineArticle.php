<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Enums\Magazine\MagazineArticleType;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'issue_id',
    'author_id',
    'title',
    'slug',
    'subtitle',
    'excerpt',
    'content',
    'type',
    'byline',
    'position',
    'featured',
    'status',
    'published_at',
    'views',
    'reading_time',
])]
class MagazineArticle extends Model
{
    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'position' => 'integer',
            'featured' => 'boolean',
            'status' => MagazineArticleStatus::class,
            'type' => MagazineArticleType::class,
            'published_at' => 'datetime',
            'views' => 'integer',
            'reading_time' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope published articles.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where(
                'status',
                MagazineArticleStatus::Published
            )
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere(
                        'published_at',
                        '<=',
                        now()
                    );
            });
    }

    /**
     * Scope featured articles.
     */
    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where(
            'featured',
            true
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Article issue.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(
            MagazineIssue::class,
            'issue_id',
        );
    }

    /**
     * Article author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id',
        );
    }

    /**
     * Article media.
     */
    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable',
        );
    }

    /**
     * Article categories.
     */
    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable',
        );
    }

    /**
     * Article tags.
     */
    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
        );
    }

    /**
     * Article SEO metadata.
     */
    public function seo(): MorphMany
    {
        return $this->morphMany(
            SeoMetadata::class,
            'seoable',
        );
    }
}