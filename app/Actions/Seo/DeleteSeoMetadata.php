<?php

namespace App\Actions\Seo;

use App\Models\SeoMetadata;

class DeleteSeoMetadata
{
    public function handle(SeoMetadata $seo): void
    {
        $seo->delete();
    }
}