<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Models\Media;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

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

    public function scopePublished(Builder $query): Builder
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

    public function media(): MorphMany
    {
        return $this->morphMany(
            Media::class,
            'mediable',
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