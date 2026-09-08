<?php

namespace App\Enums\Interview;

use App\Traits\Enums\HasDropdown;

enum InterviewType: string
{
    use HasDropdown;

    case WRITTEN = 'written';
    case AUDIO = 'audio';
    case VIDEO = 'video';

    public function label(): string
    {
        return match ($this) {
            self::WRITTEN => 'Written',
            self::AUDIO => 'Audio',
            self::VIDEO => 'Video',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::WRITTEN => '#2563EB',
            self::AUDIO => '#D97706',
            self::VIDEO => '#7C3AED',
        };
    }

}
