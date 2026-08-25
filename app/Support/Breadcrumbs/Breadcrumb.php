<?php

namespace App\Support\Breadcrumbs;

use JsonSerializable;

final readonly class Breadcrumb implements JsonSerializable
{
    public function __construct(
        public string $title,
        public ?string $href = null,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'title' => $this->title,
            'href' => $this->href,
        ];
    }
}
