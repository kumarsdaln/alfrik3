<?php

namespace App\Models\Magazine;

use App\Enums\Magazine\MagazineIssueStatus;
use App\Enums\Magazine\MagazineStatus;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
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
    use HasCategories;
    use HasTags;
    use HasMedia;
    use HasSeo;
    use HasParticipants;

    /*
    |--------------------------------------------------------------------------
    | Casts
    |--------------------------------------------------------------------------
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

    #[Scope]
    protected function published(Builder $query): void
    {
        $query
            ->where('status', MagazineStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    #[Scope]
    protected function featured(Builder $query): void
    {
        $query->where('featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function issues(): HasMany
    {
        return $this->hasMany(MagazineIssue::class);
    }

    public function publishedIssues(): HasMany
    {
        return $this->issues()
            ->where('status', MagazineIssueStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }
}
