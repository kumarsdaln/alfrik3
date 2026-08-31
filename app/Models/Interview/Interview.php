<?php

namespace App\Models\Interview;

use App\Enums\Interview\Status;
use App\Enums\Interview\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'title',
    'slug',
    'description',
    'interview_type',
    'status',
    'thumbnail',
    'duration',
    'published_at',
    'created_by',
])]
class Interview extends Model
{
    /**
     * Attributes appended to the model's array / JSON representation.
     */
    protected $appends = [
        'formatted_duration',
        'action_label',
    ];

    /**
     * Attribute casting.
     */
    protected $casts = [
        'duration' => 'integer',
        'published_at' => 'date',
        'status' => Status::class,
        'interview_type' => Type::class,
    ];

    /*
    |--------------------------------------------------------------------------
    | Accessors
    |--------------------------------------------------------------------------
    */

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn (): string => $this->duration
                ? self::formatDuration((int) $this->duration)
                : '-',
        );
    }

    protected function actionLabel(): Attribute
    {
        return Attribute::make(
            get: fn (): string => match ($this->interview_type) {
                Type::WRITTEN => 'Read',
                Type::AUDIO => 'Listen',
                Type::VIDEO => 'Watch',
            },
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    /**
     * User who created the interview.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by',
        );
    }

    /**
     * People participating in the interview.
     *
     * An interview can have multiple participants.
     */
    public function participants(): HasMany
    {
        return $this->hasMany(
            InterviewParticipant::class,
            'interview_id',
        );
    }

    /**
     * Questions belonging to the interview.
     */
    public function questions(): HasMany
    {
        return $this->hasMany(
            InterviewQuestion::class,
            'interview_id',
        )->orderBy('order');
    }

    /**
     * Media attached to the interview.
     */
    public function media(): HasMany
    {
        return $this->hasMany(
            InterviewMedia::class,
            'interview_id',
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Helpers
    |--------------------------------------------------------------------------
    */

    protected static function formatDuration(int $seconds): string
    {
        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf(
                '%dh %02dm',
                $hours,
                $minutes,
            );
        }

        if ($minutes > 0) {
            return sprintf(
                '%dm',
                $minutes,
            );
        }

        return sprintf(
            '%ds',
            $remainingSeconds,
        );
    }
}