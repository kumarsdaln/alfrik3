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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

#[Fillable([
    'issue_id',
    'title',
    'slug',
    'subtitle',
    'excerpt',
    'content',
    'type',
    'position',
    'featured',
    'status',
    'published_at',
])]
class MagazineArticle extends Model
{
    use HasCategories;
    use HasTags;
    use HasMedia;
    use HasSeo;
    use HasParticipants;

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
    */

    protected function casts(): array
    {
        return [
            'type' => MagazineArticleType::class,
            'status' => MagazineArticleStatus::class,
            'position' => 'integer',
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    #[Scope]
    protected function published(Builder $query): void
    {
        $query
            ->where('status', MagazineArticleStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function issue(): BelongsTo
    {
        return $this->belongsTo(
            MagazineIssue::class,
            'issue_id',
            'id',
        );
    }

    public function magazine(): HasOneThrough
    {
        return $this->hasOneThrough(
            Magazine::class,
            MagazineIssue::class,
            'id',
            'id',
            'issue_id',
            'magazine_id',
        );
    }
}
