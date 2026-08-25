<?php

namespace App\Support\Breadcrumbs;

class BreadcrumbBuilder
{
    /**
     * @var Breadcrumb[]
     */
    private array $items = [];


    public static function make(): self
    {
        return new self();
    }


    public function add(
        string $title,
        ?string $href = null,
    ): self {
        $this->items[] = new Breadcrumb(
            title: $title,
            href: $href,
        );

        return $this;
    }


    public function home(): self
    {
        return $this->add(
            title: 'Home',
            href: route('home'),
        );
    }


    /**
     * @return Breadcrumb[]
     */
    public function get(): array
    {
        return $this->items;
    }


    public function toArray(): array
    {
        return array_map(
            fn(Breadcrumb $breadcrumb) => $breadcrumb->jsonSerialize(),
            $this->items,
        );
    }
}
