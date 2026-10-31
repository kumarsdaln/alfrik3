<?php

namespace App\Traits;

use App\Models\SeoMetadata;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasSeo
{
    public function seo(): MorphMany
    {
        return $this->morphMany(
            SeoMetadata::class,
            'seoable',
        );
    }
}