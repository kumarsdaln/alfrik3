<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Models\Media;
use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Attributes\Fillable;
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
    'download_count',
    'status',
    'featured',
])]
class MagazineIssue extends Model
{
    protected function casts(): array
    {
        return [
            'volume' => 'integer',
            'issue_number' => 'integer',
            'cover_date' => 'date',
            'published_at' => 'datetime',
            'download_count' => 'integer',
            'status' => MagazineIssueStatus::class,
            'featured' => 'boolean',
        ];
}
    public function magazine(): BelongsTo
    {
        return $this->belongsTo(
            Magazine::class,
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