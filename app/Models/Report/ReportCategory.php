<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ReportCategory extends Model
{
    protected $table = 'report_categories';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'description'];

    public function reports()
    {
        return $this->hasMany(Report::class, 'category_id');
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'category';
        $slug = $base;
        $i = 1;

        while (
            static::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base.'-'.$i++;
        }

        return $slug;
    }
}
