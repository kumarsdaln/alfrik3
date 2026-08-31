<?php

namespace App\Models\Survey;

use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

#[Fillable([
    'title',
    'slug',
    'description',
    'status',
    'published_at',
    'closes_at',
    'allow_anonymous',
    'one_response_per_user',
    'show_results',
    'author_id',
])]
class Survey extends Model
{

    protected $casts = [
        'status' => 'boolean',
        'allow_anonymous' => 'boolean',
        'one_response_per_user' => 'boolean',
        'show_results' => 'boolean',
        'published_at' => 'datetime',
        'closes_at' => 'datetime',
    ];

    public function questions()
    {
        return $this->hasMany(SurveyQuestion::class)
            ->orderBy('position');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }
    
    #[Scope]
    public function scopePublished(Builder $query): Builder
    {
        return $query
            ->where('status', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    #[Scope]
    public function open(Builder $query): Builder
    {
        return $query
            ->published()
            ->where(function (Builder $q) {
                $q->whereNull('closes_at')
                    ->orWhere('closes_at', '>', now());
            });
    }

    public function isOpen(): bool
    {
        if (! $this->status) {
            return false;
        }

        if ($this->published_at && $this->published_at->isFuture()) {
            return false;
        }

        if ($this->closes_at && $this->closes_at->isPast()) {
            return false;
        }

        return true;
    }

    public static function uniqueSlug(
        string $title,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($title) ?: 'survey';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when(
                    $ignoreId,
                    fn ($q) => $q->where('id', '!=', $ignoreId)
                )
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}