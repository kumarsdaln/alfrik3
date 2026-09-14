<?php

namespace App\Services\Seo\Schemas;

class ReportSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'Report',
            '@id' => "{$data['url']}#report",
            'url' => $data['url'],
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'author' => $data['author'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'datePublished' => $data['date_published'] ?? null,
            'dateModified' => $data['date_modified'] ?? null,
            'about' => $data['about'] ?? null,
            'reportNumber' => $data['report_number'] ?? null,
        ], fn ($value) => $value !== null);
    }
}