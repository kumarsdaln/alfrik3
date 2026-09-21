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
    public function issue(): BelongsTo
    {
        return $this->belongsTo(
            MagazineIssue::class,
            'issue_id',
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id',
        );
    }

    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable',
        );
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable',
        );
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
        );
    }

    public function seo(): MorphMany
    {
        return $this->morphMany(
            SeoMetadata::class,
            'seoable',
        );
    }
}