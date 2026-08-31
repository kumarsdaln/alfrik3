<?php

namespace App\Models\Magazine;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'issue_id',
    'author_id',

    'title',
    'slug',
    'subtitle',

    'excerpt',
    'content',

    'cover_image',

    'type',
    'byline',

    'position',
    'featured',

    'status',
    'published_at',

    'views',
    'reading_time',

    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class MagazineArticle extends Model
{
    use HasFactory;

    protected $casts = [
        'issue_id' => 'integer',
        'author_id' => 'integer',

        'position' => 'integer',

        'featured' => 'boolean',
        'status' => 'boolean',

        'published_at' => 'datetime',

        'views' => 'integer',
        'reading_time' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The issue this article belongs to.
     */
    public function issue(): BelongsTo
    {
        return $this->belongsTo(
            MagazineIssue::class,
            'issue_id'
        );
    }

    /**
     * The author of the article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query
            ->orderBy('position')
            ->orderByDesc('published_at');
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getReadingTimeLabelAttribute(): ?string
    {
        if (! $this->reading_time) {
            return null;
        }

        return $this->reading_time . ' min read';
    }

    /*
    |--------------------------------------------------------------------------
    | Route Model Binding
    |--------------------------------------------------------------------------
    */

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}