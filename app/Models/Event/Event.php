<?php

namespace App\Models\Event;

use App\Enums\Event\EventStatus;
use App\Enums\Event\EventType;
use App\Enums\Event\EventVisibility;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    'banner',
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
    ];

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopePublic(Builder $query): Builder
    {
        return $query->where('visibility', 'public');
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
                ->whereNotNull('end_date')
                ->where('end_date', '<', now());
        })->orWhere(function (Builder $query) {
            $query
                ->whereNull('end_date')
                ->where('start_date', '<', now());
        });
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
        );
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(
            EventCategory::class,
            'event_category_map',
            'event_id',
            'category_id',
        );
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(
            EventSession::class,
        )->orderBy('position');
    }

    public function participants(): HasMany
    {
        return $this->hasMany(
            EventParticipant::class,
        )->orderBy('position');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(
            EventTicket::class,
        )->orderBy('position');
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(
            EventRegistration::class,
        );
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(
            EventReview::class,
        );
    }

    public function media(): HasMany
    {
        return $this->hasMany(
            EventMedia::class,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Check-ins
    |--------------------------------------------------------------------------
    |
    | Check-ins belong to registrations, not directly to events.
    |
    */
}