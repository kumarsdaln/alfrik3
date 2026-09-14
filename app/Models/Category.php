<?php

namespace App\Models;

use App\Models\Report\Report;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Table('categories')]
#[Fillable([
    'name',
    'slug',
    'description',
    'parent_id',
    'status',
    'sort_order',
])]
class Category extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(
            Category::class,
            'parent_id',
        );
    }

    public function children(): HasMany
    {
        return $this->hasMany(
            Category::class,
            'parent_id',
        );
    }

    public function reports(): MorphToMany
    {
        return $this->morphedByMany(
            Report::class,
            'categorizable',
        );
    }
}