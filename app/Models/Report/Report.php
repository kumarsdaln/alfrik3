<?php

namespace App\Models\Report;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Models\Research\Research;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

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
    'report_date',
])]
class Report extends Model
{
    use HasFactory;
    use HasCategories, HasTags, HasMedia, HasSeo, HasParticipants;

    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'status' => ReportStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
            'report_date' => 'datetime',
        ];
    }

    #[Scope]
    protected function published(Builder $query)
    {
        $query->where('status', ReportStatus::Published);
    }

    #[Scope]
    protected function featured(Builder $query)
    {
        $query->where('featured', true);
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */
    public function sections(): HasMany
    {
        return $this->hasMany(ReportSection::class)
            ->orderBy('position');
    }
}
