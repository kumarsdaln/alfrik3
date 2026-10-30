<?php

namespace App\Models\Report;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'report_id',
    'title',
    'subtitle',
    'content',
    'position',
])]
class ReportSection extends Model
{
    use HasFactory;


    protected function casts(): array
    {
        return [
            'position' => 'integer',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(Report::class);
    }

    public function contentBlocks(): HasMany
    {
        return $this->hasMany(ReportContentBlock::class)
            ->orderBy('position');
    }
}
