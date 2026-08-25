<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder as EloquentBuilder;

class TagsFilter
{
    protected $values;
    protected $relation;
    protected $column;

    /**
     * @param array|string|null $values   Tag IDs/slugs (can be array or comma string)
     * @param string $relation            Relation name (default: "tags")
     * @param string $column              Column in tags table (default: "id")
     */
    public function __construct($values, string $relation = 'tags', string $column = 'id')
    {
        if (is_string($values)) {
            $values = array_filter(explode(',', $values)); // convert "1,2,3" → [1,2,3]
        }

        $this->values   = $values;
        $this->relation = $relation;
        $this->column   = $column;
    }

    public function __invoke(EloquentBuilder $query, $next)
    {
        if (!empty($this->values)) {
            $query->whereHas($this->relation, function ($q) {
                $q->whereIn($this->column, $this->values);
            });
        }

        return $next($query);
    }
}
