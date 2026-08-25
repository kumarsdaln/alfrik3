<?php

namespace App\Models\Interview;

use App\Enums\Interview\Status;
use App\Enums\Interview\Type;
use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'interview_type',
        'status',
        'thumbnail',
        'duration',
        'published_at',
        'created_by'
    ];

    protected $appends = [
        'formatted_duration',
        'action_label'
    ];

    protected $casts = [
        'published_at' => 'date',
        'status' => Status::class,
        'interview_type' => Type::class
    ];

    protected function formattedDuration(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->duration
            ? self::formatDuration($this->duration)
            : '-',
        );
    }

    protected static function formatDuration(int $seconds): string
    {

        $hours = intdiv($seconds, 3600);
        $minutes = intdiv($seconds % 3600, 60);
        $remainingSeconds = $seconds % 60;

        if ($hours > 0) {
            return sprintf(
                '%dh %02dm',
                $hours,
                $minutes
            );
        }

        if ($minutes > 0) {
            return sprintf(
                '%dm',
                $minutes
            );
        }

        return sprintf(
            '%ds',
            $remainingSeconds
        );
    }

    protected function actionLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->interview_type) {
                Type::WRITTEN => 'Read',
                Type::AUDIO => 'Listen',
                Type::VIDEO => 'Watch',
            },
        );
    }

    public function createdBy() {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function participants() {
        return $this->hasMany(InterviewParticipant::class, 'interview_id', 'id');
    }

    public function questions() {
        return $this->hasMany(InterviewQuestion::class)->orderBy('order');
    }

    public function media() {
        return $this->hasMany(InterviewMedia::class);
    }
}
