<?php

namespace App\Traits\Enums;

trait HasDropdown
{
    public static function dropdown(): array
    {
        return collect(self::cases())
            ->map(fn ($case) => $case->option())
            ->values()
            ->toArray();
    }

    public function option(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'color' => method_exists($this, 'color')
                ? $this->color()
                : null,
        ];
    }
}