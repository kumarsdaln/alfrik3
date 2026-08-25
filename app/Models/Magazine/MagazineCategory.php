<?php

namespace App\Models\Magazine;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MagazineCategory extends Model
{
    use HasFactory;

    protected $table = 'magazine_categories';

    protected $primaryKey = 'id';

    public $timestamps = false;

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'description',
        'meta_title',
        'meta_description',
        'meta_keywords',
    ];

    public function magazines()
    {
        return $this->hasMany(Magazine::class, 'category_id', 'id');
    }

    public function publishedMagazines()
    {
        return $this->magazines()->where('status', true);
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
