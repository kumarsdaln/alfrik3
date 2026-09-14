<?php

namespace App\Services\Seo\Schemas;

interface SchemaBuilder
{
    public function build(array $data): array;
}