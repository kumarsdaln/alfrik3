<?php

namespace App\Models\Magazine;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Magazine extends Model
{
    use HasFactory;

    protected $table = 'magazine';

    protected $primaryKey = 'id';

    protected $fillable = [
        'title',
        'subtitle',
        'content',
        'cover_image',
        'category_id',
        'author_id',
        'status',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'slug',
    ];

    protected $casts = [
        'status' => 'boolean',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected $appends = ['reading_minutes'];

    public function category()
    {
        return $this->belongsTo(MagazineCategory::class, 'category_id', 'id');
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Live issues: toggled on and either unscheduled or past their publish time.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Toggled on but scheduled for a future publish time.
     */
    public function scopeScheduled(Builder $query): Builder
    {
        return $query->where('status', true)
            ->whereNotNull('published_at')
            ->where('published_at', '>', now());
    }

    /**
     * Decoded content sections: [{ section, content, subsections? }, ...].
     *
     * @return array<int, array<string, mixed>>
     */
    public function getSectionsAttribute(): array
    {
        if (blank($this->content)) {
            return [];
        }

        $decoded = json_decode($this->content, true);

        return is_array($decoded) && isset($decoded['sections']) && is_array($decoded['sections'])
            ? $decoded['sections']
            : [];
    }

    /**
     * Rough reading time across all section HTML (~200 wpm).
     */
    public function getReadingMinutesAttribute(): int
    {
        $text = collect($this->sections)->pluck('content')->implode(' ');
        $words = str_word_count(strip_tags($text));

        return max(1, (int) ceil($words / 200));
    }

    /**
     * Build a unique slug from a title, optionally ignoring a record id.
     */
    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'issue';
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
