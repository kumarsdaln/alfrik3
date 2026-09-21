<?php

namespace App\Models\Report;

use App\Enums\Report\ReportContentBlockType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'report_section_id',
    'type',
    'title',
    'description',
    'content',
    'position',
])]
class ReportContentBlock extends Model
{
    use HasFactory;


    protected function casts(): array
    {
        return [
            'type' => ReportContentBlockType::class,
            'content' => 'array',
            'position' => 'integer',
        ];
    }

    public function section(): BelongsTo
    {
        return $this->belongsTo(
            ReportSection::class,
            'report_section_id',
        );
    }
}
