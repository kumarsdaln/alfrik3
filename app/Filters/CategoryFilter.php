<?php

namespace App\Filters;

use Closure;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class CategoryFilter
{
    protected string $key;
    protected $value;

    public function __construct(string $key, $value)
    {
        $this->key   = $key;
        $this->value = $value;
    }

    public function __invoke(EloquentBuilder $query, Closure $next)
    {
        // Apply only when a value is provided (including 0)
        if (!is_null($this->value) && $this->value !== '') {
            $query->where($this->key, $this->value);
        }

        return $next($query);
    }
}