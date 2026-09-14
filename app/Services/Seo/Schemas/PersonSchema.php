<?php

namespace App\Services\Seo\Schemas;

class PersonSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'Person',
            '@id' => ($data['url'] ?? '').'#person',

            'url' => $data['url'] ?? null,
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'jobTitle' => $data['job_title'] ?? null,
            'worksFor' => $data['works_for'] ?? null,
            'sameAs' => $data['same_as'] ?? null,
        ], fn ($value) => $value !== null);
    }
}