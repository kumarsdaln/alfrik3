<?php
namespace App\Filters;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class ContentCategoryFilter
{
    protected $category;

    public function __construct($category)
    {
        $this->category = $category;
    }

    public function __invoke(EloquentBuilder $query, $next)
    {
        if (!$this->category) {
            return $next($query); 
        }
        $query->whereHas('category', function ($q) {
            $q->where('slug', $this->category);
        });
       return $next($query); 
    }
}