<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Enums\Magazine\MagazineArticleType;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'issue_id',
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
    use HasCategories, HasTags, HasMedia, HasSeo, HasParticipants;
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
    #[Scope]
    public function published(Builder $query): Builder
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
    public function featured(Builder $query): Builder
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
}