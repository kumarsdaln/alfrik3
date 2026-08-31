<?php

namespace App\Models\Research;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'abstract',
    'authors',
    'author_id',
    'area_id',
    'institution',
    'methodology',
    'doi',
    'citation',
    'keywords',
    'cover_image',
    'file_path',
    'file_size',
    'file_type',
    'published_at',
    'status',
    'featured',
    'download_count',
    'meta_title',
    'meta_description',
    'meta_keywords',
])]
class ResearchPaper extends Model
{
    protected $table = 'research_papers';

    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'published_at' => 'date',
        'file_size' => 'integer',
        'download_count' => 'integer',
    ];

    protected $appends = [
        'file_size_label',
    ];

    public function area(): BelongsTo
    {
        return $this->belongsTo(
            ResearchArea::class,
            'area_id'
        );
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'author_id'
        );
    }

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

    public static function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($title) ?: 'research';
        $slug = $base;
        $i = 1;

        while (
            static::query()
            ->where('slug', $slug)
            ->when(
                $ignoreId,
                fn($query) => $query->where('id', '!=', $ignoreId)
            )
            ->exists()
        ) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
