<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineStatus;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'title',
    'slug',
    'subtitle',
    'description',
    'author_id',
    'status',
    'featured',
    'published_at',
])]
class Magazine extends Model
{
    protected function casts(): array
    {
        return [
            'status' => MagazineStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id',
        );
    }

    public function issues(): HasMany
    {
        return $this->hasMany(
            MagazineIssue::class,
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