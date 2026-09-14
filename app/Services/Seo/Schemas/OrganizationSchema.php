<?php

namespace App\Services\Seo\Schemas;

class OrganizationSchema implements SchemaBuilder
{
    public function build(array $data): array
    {
        return array_filter([
            '@type' => 'Organization',
            '@id' => ($data['url'] ?? '').'#organization',

            'url' => $data['url'] ?? null,
            'name' => $data['name'] ?? null,
            'description' => $data['description'] ?? null,
            'logo' => $data['logo'] ?? null,
            'image' => $data['image'] ?? null,
            'sameAs' => $data['same_as'] ?? null,
        ], fn ($value) => $value !== null);
    }
}