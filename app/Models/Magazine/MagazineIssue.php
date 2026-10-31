<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Traits\HasMedia;
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
    'volume',
    'issue_number',
    'cover_date',
    'published_at',
    'description',
    'editor',
    'file_path',
    'file_size',
    'file_type',
    'download_count',
    'status',
    'featured',
])]
class MagazineIssue extends Model
{
    use HasMedia, HasSeo;
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
            'file_size' => 'integer',
            'download_count' => 'integer',
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
    protected function published(Builder $query): Builder
    {
        return $query
            ->where(
                'status',
                MagazineIssueStatus::Published
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

    #[Scope]
    protected function featured(Builder $query): Builder
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

    public function magazine(): BelongsTo
    {
        return $this->belongsTo(
            Magazine::class,
            'magazine_id',
        );
    }

    public function articles(): HasMany
    {
        return $this->hasMany(
            MagazineArticle::class,
            'issue_id',
        )->orderBy('position');
    }
}