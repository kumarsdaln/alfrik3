<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineStatus;
use App\Models\Category;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'subtitle',
    'description',
    'status',
    'featured',
    'published_at',
])]
class Magazine extends Model
{
    use HasCategories, HasTags, HasMedia, HasSeo, HasParticipants;
    /**
     * Attribute casts.
     */
    protected function casts(): array
    {
        return [
            'status' => MagazineStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    /**
     * Scope published magazines.
     */
    #[Scope]
    protected function published(Builder $query): Builder
    {
        return $query
            ->where('status', MagazineStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    /**
     * Scope featured magazines.
     */
    #[Scope]
    protected function featured(Builder $query): Builder
    {
        return $query->where('featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * Magazine issues.
     */
    public function issues(): HasMany
    {
        return $this->hasMany(
            MagazineIssue::class,
        );
    }
}