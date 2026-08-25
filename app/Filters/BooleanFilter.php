<?php
namespace App\Filters;
use Closure;
use Illuminate\Database\Eloquent\Builder;

class BooleanFilter
{
    public function __construct(private string $key, private ?bool $value) {
        
    }

    public function __invoke(Builder $query , Closure $next)
    {
        if (!is_null($this->value)) {
            $query->where($this->key, $this->value);
        }
       return $next($query); 
    }
}