<?php

namespace App\Models\Magazine;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
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
    'cover_image',
    'file_path',
    'file_size',
    'file_type',
    'download_count',
    'status',
    'featured',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class MagazineIssue extends Model
{
    use HasFactory;

    protected $casts = [
        'magazine_id' => 'integer',
        'volume' => 'integer',
        'issue_number' => 'integer',
        'cover_date' => 'date',
        'published_at' => 'datetime',
        'file_size' => 'integer',
        'download_count' => 'integer',
        'status' => 'boolean',
        'featured' => 'boolean',
    ];

    protected $appends = [
        'file_size_label',
        'edition_label',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * The magazine this issue belongs to.
     */
    public function magazine(): BelongsTo
    {
        return $this->belongsTo(
            Magazine::class,
            'magazine_id'
        );
    }

    /**
     * Articles contained in this issue.
     */
    public function articles(): HasMany
    {
        return $this->hasMany(
            MagazineArticle::class,
            'issue_id'
        )->orderBy('position');
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

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getFileSizeLabelAttribute(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $mb = $this->file_size / (1024 * 1024);

        return $mb >= 1
            ? round($mb, 1) . ' MB'
            : max(
                1,
                (int) round($this->file_size / 1024)
            ) . ' KB';
    }

    public function getEditionLabelAttribute(): ?string
    {
        if (! $this->volume && ! $this->issue_number) {
            return null;
        }

        $parts = [];

        if ($this->volume) {
            $parts[] = 'Vol. ' . $this->volume;
        }

        if ($this->issue_number) {
            $parts[] = 'Issue ' . $this->issue_number;
        }

        return implode(' · ', $parts);
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