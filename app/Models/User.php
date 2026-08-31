<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Models\Event\Event;
use App\Models\Event\EventCheckin;
use App\Models\Event\EventParticipant;
use App\Models\Event\EventRegistration;
use App\Models\Event\EventReview;
use App\Models\Event\EventSession;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Laravel\Fortify\Contracts\PasskeyUser;
use Laravel\Fortify\PasskeyAuthenticatable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Scout\Searchable;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property Carbon|null $two_factor_confirmed_at
 * @property string|null $remember_token
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
#[Fillable([
    'name',
    'username',
    'email',
    'avatar',
    'headline',
    'password',
    'is_active',
    'country_id',
    'position_id'
])]
#[Hidden(['password', 'two_factor_secret', 'two_factor_recovery_codes', 'remember_token'])]
class User extends Authenticatable implements PasskeyUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable, PasskeyAuthenticatable, TwoFactorAuthenticatable, Searchable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'two_factor_confirmed_at' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Scope the query to active users.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope the query to inactive users.
     */
    #[Scope]
    protected function inactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function hasRole(string $role): bool
    {
        return $this->roles()
            ->where('slug', $role)
            ->exists();
    }

    public function hasAnyRole(array $roles): bool
    {
        return $this->roles()
            ->whereIn('slug', $roles)
            ->exists();
    }

    public function hasAllRoles(array $roles): bool
    {
        return $this->roles()
            ->whereIn('slug', $roles)
            ->count() === count($roles);
    }

    public function isSuperAdmin(): bool
    {
        return $this->hasRole('super-admin');
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        return $this->roles()
            ->whereHas('permissions', function ($query) use ($permission) {
                $query->where('slug', $permission);
            })
            ->exists();
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function languages(): BelongsToMany
    {
        return $this->belongsToMany(
            Language::class,
            'user_languages',
            'user_id',
            'language_id',
        )->withTimestamps();
    }

    public function industries(): BelongsToMany
    {
        return $this->belongsToMany(
            Industry::class,
            'industry_user',
            'user_id',
            'industry_id',
        )->withTimestamps();
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function toSearchableArray(): array
    {
        return [
            'name' => $this->name,
            'username' => $this->username,
            'headline' => $this->headline,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Events
    |--------------------------------------------------------------------------
    */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(
            Event::class,
            'created_by',
        );
    }

    public function eventParticipations(): HasMany
    {
        return $this->hasMany(
            EventParticipant::class,
        );
    }

    public function eventRegistrations(): HasMany
    {
        return $this->hasMany(
            EventRegistration::class,
        );
    }

    public function eventReviews(): HasMany
    {
        return $this->hasMany(
            EventReview::class,
        );
    }

    public function eventCheckins(): HasMany
    {
        return $this->hasMany(
            EventCheckin::class,
            'checked_in_by',
        );
    }

    public function eventSessions(): BelongsToMany
    {
        return $this->belongsToMany(
            EventSession::class,
            'session_speakers',
            'user_id',
            'session_id',
        )->withPivot('position')
            ->orderBy('position');
    }
}
