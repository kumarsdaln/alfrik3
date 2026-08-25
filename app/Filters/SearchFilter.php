<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class SearchFilter
{
    public function __construct(private ?string $key, private ?string $value) {
        
    }

    public function __invoke(EloquentBuilder $query, $next)
    {
        if (is_null($this->value) || $this->value === '') {
            return $next($query);
        }
       $query->whereLike($this->key, "%$this->value%");
       return $next($query); 
    }
}