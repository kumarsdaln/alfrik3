<?php

namespace App\Models\Research;

use App\Enums\Research\ResearchStatus;
use App\Enums\Research\ResearchType;
use App\Models\Report\Report;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Table;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[Fillable([
    'title',
    'slug',
    'subtitle',
    'description',
    'summary',
    'type',
    'status',
    'featured',
    'published_at',
])]
#[Table('researches')]
class Research extends Model
{
    use HasFactory;
    use HasCategories, HasTags, HasMedia, HasSeo, HasParticipants;

    protected function casts(): array
    {
        return [
            'type' => ResearchType::class,
            'status' => ResearchStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
        ];
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

    public function methodology(): HasOne
    {
        return $this->hasOne(ResearchMethodology::class);
    }
}
