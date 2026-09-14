<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Table('seo_metadata')]
#[Fillable([
    'title',
    'description',
    'canonical_url',
    'indexable',
    'followable',
    'og_title',
    'og_description',
    'og_type',
    'og_image_url',
    'twitter_card',
    'twitter_title',
    'twitter_description',
    'twitter_image_url',
    'locale',
    'schema_type',
])]
class SeoMetadata extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'indexable' => 'boolean',
            'followable' => 'boolean',
        ];
    }

    public function seoable(): MorphTo
    {
        return $this->morphTo();
    }
}
