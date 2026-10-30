<?php

namespace App\Enums\Media;

use App\Traits\Enums\HasDropdown;

enum MediaCollection: string
{
    use HasDropdown;

    case DEFAULT = 'default';
    case THUMBNAIL = 'thumbnail';
    case COVER = 'cover';
    case AVATAR = 'avatar';
    case FEATURED = 'featured';
    case GALLERY = 'gallery';
    case CONTENT = 'content';

    public function label(): string
    {
        return match ($this) {
            self::DEFAULT => 'Default',
            self::THUMBNAIL => 'Thumbnail',
            self::COVER => 'Cover',
            self::AVATAR => 'Avatar',
            self::FEATURED => 'Featured',
            self::GALLERY => 'Gallery',
            self::CONTENT => 'Content',
        };
    }
}
