<?php

namespace App\Models\Report;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'summary',
    'cover_image',
    'file_path',
    'file_size',
    'file_type',
    'category_id',
    'author_id',
    'report_year',
    'published_at',
    'status',
    'featured',
    'gated',
    'download_count',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class Report extends Model
{
    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'gated' => 'boolean',
        'published_at' => 'datetime',
        'download_count' => 'integer',
        'file_size' => 'integer',
        'report_year' => 'integer',
    ];

    protected $appends = [
        'file_size_label',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            ReportCategory::class,
            'category_id'
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id'
        );
    }

    /**
     * Published reports.
     *
     * A report is published when it is active and either:
     * - has no scheduled publication date, or
     * - its publication date has passed.
     */
    #[Scope]
    public function published(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scheduled reports.
     */
    #[Scope]
    public function scheduled(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    /**
     * Featured reports.
     */
    #[Scope]
    public function featured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /**
     * Human-readable file size.
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

    /**
     * Generate a unique slug.
     */
    public static function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($title) ?: 'report';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
            ->when(
                $ignoreId,
                fn($query) => $query->where(
                    'id',
                    '!=',
                    $ignoreId
                )
            )
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
