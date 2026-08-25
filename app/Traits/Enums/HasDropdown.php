<?php

namespace App\Traits\Enums;

trait HasDropdown
{
    public static function dropdown(): array
    {
        return collect(self::cases())
            ->map(fn ($case) => [
                'value' => $case->value,
                'label' => $case->label(),
                'color' => method_exists($case, 'color')
                    ? $case->color()
                    : null,
            ])
            ->values()
            ->toArray();
    }
}