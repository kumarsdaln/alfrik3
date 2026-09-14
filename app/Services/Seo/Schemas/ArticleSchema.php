<?php

namespace App\Services\Seo\Schemas;

class ArticleSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'Article',
            '@id' => ($data['url'] ?? '').'#article',
            'url' => $data['url'] ?? null,
            'headline' => $data['headline'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'author' => $data['author'] ?? null,
            'publisher' => $data['publisher'] ?? null,
            'datePublished' => $data['date_published'] ?? null,
            'dateModified' => $data['date_modified'] ?? null,
            'mainEntityOfPage' => $data['main_entity_of_page'] ?? null,
            'articleSection' => $data['article_section'] ?? null,
            'keywords' => $data['keywords'] ?? null,
        ], fn ($value) => $value !== null);
    }
}