<?php

namespace App\Services\Seo\Schemas;

class WebPageSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'WebPage',
            '@id' => ($data['url'] ?? '').'#webpage',

            'url' => $data['url'] ?? null,
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'isPartOf' => $data['is_part_of'] ?? null,
            'about' => $data['about'] ?? null,
            'primaryImageOfPage' => $data['primary_image'] ?? null,
            'datePublished' => $data['date_published'] ?? null,
            'dateModified' => $data['date_modified'] ?? null,
            'inLanguage' => $data['language'] ?? null,
        ], fn ($value) => $value !== null);
    }
}