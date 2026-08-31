<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

#[Fillable([
    'name',
    'slug',
    'description',
])]
class ReportCategory extends Model
{
    protected $table = 'report_categories';

    public $timestamps = false;

    public function reports(): HasMany
    {
        return $this->hasMany(
            Report::class,
            'category_id'
        );
    }

    /**
     * Generate a unique category slug.
     */
    public static function uniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
            ->when(
                $ignoreId,
                fn($query) => $query->where(
                    'id',
                    '!=',
                    $ignoreId
                )
            )
            ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
