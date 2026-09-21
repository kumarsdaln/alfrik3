<?php

namespace App\Models\Report;

use App\Enums\Report\ReportVersionStatus;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'report_id',
    'version',
    'title',
    'subtitle',
    'description',
    'summary',
    'status',
    'created_by',
    'published_by',
    'published_at',
    'change_summary',
])]
class ReportVersion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'version' => 'integer',
            'status' => ReportVersionStatus::class,
            'published_at' => 'datetime',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function publisher(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'published_by'
        );
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ReportVersionSection::class)
            ->orderBy('position');
    }
}
