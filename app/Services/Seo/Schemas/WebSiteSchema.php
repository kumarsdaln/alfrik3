<?php

namespace App\Services\Seo\Schemas;

class WebSiteSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'WebSite',
            '@id' => ($data['url'] ?? '').'#website',

            'url' => $data['url'] ?? null,
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'inLanguage' => $data['language'] ?? null,
            'publisher' => $data['publisher'] ?? null,
        ], fn ($value) => $value !== null);
    }
}