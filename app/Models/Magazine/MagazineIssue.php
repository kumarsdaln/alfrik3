<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineArticleStatus;
use App\Enums\Magazine\MagazineIssueStatus;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'magazine_id',
    'title',
    'slug',
    'subtitle',
    'description',
    'volume',
    'issue_number',
    'cover_date',
    'published_at',
    'status',
    'featured',
])]
class MagazineIssue extends Model
{
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
            'volume' => 'integer',
            'issue_number' => 'integer',
            'cover_date' => 'date',
            'published_at' => 'datetime',
            'status' => MagazineIssueStatus::class,
            'featured' => 'boolean',
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
            ->where('status', MagazineIssueStatus::Published)
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

    public function magazine(): BelongsTo
    {
        return $this->belongsTo(Magazine::class);
    }

    public function articles(): HasMany
    {
        return $this->hasMany(
            MagazineArticle::class,
            'issue_id',
            'id',
        )->orderBy('position');
    }

    public function publishedArticles(): HasMany
    {
        return $this->articles()
            ->where('status', MagazineArticleStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
