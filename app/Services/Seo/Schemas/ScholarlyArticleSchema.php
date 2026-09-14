<?php

namespace App\Services\Seo\Schemas;

class ScholarlyArticleSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'ScholarlyArticle',
            '@id' => "{$data['url']}#scholarly-article",
            'url' => $data['url'] ?? null,
            'headline' => $data['headline'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'author' => $data['author'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'datePublished' => $data['date_published'] ?? null,
            'dateModified' => $data['date_modified'] ?? null,
            'isPartOf' => $data['is_part_of'] ?? null,
            'citation' => $data['citation'] ?? null,
            'sameAs' => $data['same_as'] ?? null,
        ], fn ($value) => $value !== null);
    }
}