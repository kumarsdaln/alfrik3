<?php

namespace App\Models\Event;

use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use App\Models\Category;
use App\Models\Media;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'title',
    'slug',
    'description',
    'event_type',
    'visibility',
    'start_date',
    'end_date',
    'location_name',
    'address',
    'city',
    'state',
    'country',
    'meeting_url',
    'max_attendees',
    'status',
    'created_by',
])]
class Event extends Model
{
    protected $casts = [
        'event_type' => EventType::class,
        'visibility' => EventVisibility::class,
        'status' => EventStatus::class,
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'max_attendees' => 'integer',
        'created_by' => 'integer',
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', EventStatus::PUBLISHED->value);
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', EventVisibility::PUBLIC->value);
    }

    public function scopeUpcoming(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query
                ->where('start_date', '>=', now())
                ->orWhere(function (Builder $query) {
                    $query
                        ->where('start_date', '<', now())
                        ->where(function (Builder $query) {
                            $query
                                ->whereNull('end_date')
                                ->orWhere('end_date', '>=', now());
                        });
                });
        });
    }

    public function scopePast(Builder $query): Builder
    {
        return $query->where(function (Builder $query) {
            $query
                ->where(function (Builder $query) {
                    $query
                        ->whereNotNull('end_date')
                        ->where('end_date', '<', now());
                })
                ->orWhere(function (Builder $query) {
                    $query
                        ->whereNull('end_date')
                        ->where('start_date', '<', now());
                });
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(
            Category::class,
            'categorizable',
            'categorizables',
            'categorizable_id',
            'category_id',
        );
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(
            Tag::class,
            'taggable',
            'taggables',
            'taggable_id',
            'tag_id',
        );
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(EventSession::class)
            ->orderBy('position');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(EventParticipant::class)
            ->orderBy('position');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(EventTicket::class)
            ->orderBy('position');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(EventReview::class);
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable')
            ->orderBy('collection')
            ->orderBy('id');
    }

    public function seoMetadata(): MorphMany
    {
        return $this->morphMany(SeoMetadata::class, 'seoable');
    }
}