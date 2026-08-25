<?php

namespace App\Models\Research;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ResearchArea extends Model
{
    protected $table = 'research_areas';

    public $timestamps = false;

    protected $fillable = ['name', 'slug', 'description'];

    public function papers()
    {
        return $this->hasMany(ResearchPaper::class, 'area_id');
    }

    public static function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'area';
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
