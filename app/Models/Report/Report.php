<?php

namespace App\Models\Report;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Report extends Model
{
    protected $fillable = [
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
    ];

    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'gated' => 'boolean',
        'published_at' => 'datetime',
        'download_count' => 'integer',
        'file_size' => 'integer',
    ];

    protected $appends = ['file_size_label'];

    public function category()
    {
        return $this->belongsTo(ReportCategory::class, 'category_id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Live reports: toggled on and either unscheduled or past their publish time.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /** Human-readable file size. */
    public function getFileSizeLabelAttribute(): ?string
    {
        if (! $this->file_size) {
            return null;
        }

        $mb = $this->file_size / (1024 * 1024);

        return $mb >= 1
            ? round($mb, 1).' MB'
            : max(1, (int) round($this->file_size / 1024)).' KB';
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'report';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
