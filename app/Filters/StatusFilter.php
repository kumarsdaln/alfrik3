<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder;

class StatusFilter
{
    public function __construct(
        private string $key,
        private ?string $value
    ) {}

    public function __invoke(Builder $query, Closure $next)
    {
        // Apply filter only if value is not null or empty
        if (!is_null($this->value) && $this->value !== '') {
            $query->where($this->key, $this->value);
        }

        return $next($query);
    }
}