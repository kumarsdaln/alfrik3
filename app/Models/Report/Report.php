<?php

namespace App\Models\Report;

use App\Enums\Report\ReportStatus;
use App\Enums\Report\ReportType;
use App\Traits\HasCategories;
use App\Traits\HasMedia;
use App\Traits\HasParticipants;
use App\Traits\HasSeo;
use App\Traits\HasTags;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
    use HasCategories;
    use HasTags;
    use HasMedia;
    use HasSeo;
    use HasParticipants;

    protected function casts(): array
    {
        return [
            'type' => ReportType::class,
            'status' => ReportStatus::class,
            'featured' => 'boolean',
            'published_at' => 'datetime',
            'report_date' => 'date',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Scopes
    |--------------------------------------------------------------------------
    */

    #[Scope]
    protected function published(Builder $query): void
    {
        $query
            ->where('status', ReportStatus::Published)
            ->where(function (Builder $query) {
                $query
                    ->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    #[Scope]
    protected function featured(Builder $query): void
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
        return $this->hasMany(
            ReportSection::class,
            'report_id',
            'id',
        )->orderBy('position');
    }
}
