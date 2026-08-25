<?php

namespace App\Models\Survey;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Survey extends Model
{
    protected $fillable = [
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
    ];

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
        return $this->hasMany(SurveyQuestion::class)->orderBy('position');
    }

    public function responses()
    {
        return $this->hasMany(SurveyResponse::class);
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', true)
            ->where(function (Builder $q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    /** Published and not past its close date. */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->published()
            ->where(function (Builder $q) {
                $q->whereNull('closes_at')->orWhere('closes_at', '>', now());
            });
    }

    public function isOpen(): bool
    {
        $live = $this->status && (is_null($this->published_at) || $this->published_at->lte(now()));

        return $live && (is_null($this->closes_at) || $this->closes_at->gt(now()));
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'survey';
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
