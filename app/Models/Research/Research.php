<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Models\Category;
use App\Models\Media;
use App\Models\Report\Report;
use App\Models\SeoMetadata;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

#[Fillable([
    'title',
    'slug',
    'subtitle',
    'description',
    'summary',
    'type',
    'status',
    'author_id',
    'featured',
    'published_at',
])]
class Research extends Model
{
    use HasFactory;



    protected function casts(): array
    {
        return [
            'type' => ResearchType::class,
            'status' => ResearchStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function media(): MorphMany
    {
        return $this->morphMany(Media::class, 'mediable');
    }

    public function categories(): MorphToMany
    {
        return $this->morphToMany(Category::class, 'categorizable');
    }

    public function tags(): MorphToMany
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function seo(): MorphOne
    {
        return $this->morphOne(SeoMetadata::class, 'seoable');
    }

    public function sources(): HasMany
    {
        return $this->hasMany(ResearchSource::class)
            ->orderBy('position');
    }
    public function questions(): HasMany
    {
        return $this->hasMany(ResearchQuestion::class)
            ->orderBy('position');
    }
    public function findings(): HasMany
    {
        return $this->hasMany(ResearchFinding::class)
            ->orderBy('position');
    }


    public function reports(): HasMany
    {
        return $this->hasMany(Report::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ResearchEvidence::class)
            ->orderBy('position');
    }

    public function members(): HasMany
    {
        return $this->hasMany(ResearchMember::class);
    }
}
