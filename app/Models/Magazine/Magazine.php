<?php

namespace App\Models\Magazine;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Magazine extends Model
{
    use HasFactory;

    protected $table = 'magazines';

    protected $fillable = [
        'title',
        'slug',
        'subtitle',
        'description',
        'cover_image',
        'category_id',
        'author_id',
        'status',
        'featured',
        'published_at',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    protected $casts = [
        'status' => 'boolean',
        'featured' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'is_published',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function category(): BelongsTo
    {
        return $this->belongsTo(
            MagazineCategory::class,
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

    public function issues(): HasMany
    {
        return $this->hasMany(
            MagazineIssue::class,
            'magazine_id'
        );
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
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    public function scopeDraft(Builder $query): Builder
    {
        return $query->where('status', false);
    }

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    public function getIsPublishedAttribute(): bool
    {
        return $this->status
            && $this->published_at !== null
            && $this->published_at->isPast();
    }
}