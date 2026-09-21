<?php

namespace App\Models\Research;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'research_id',
    'method',
    'description',
    'research_design',
    'data_collection_method',
    'sample_size',
    'population',
    'geography',
    'start_date',
    'end_date',
    'limitations',
])]
class ResearchMethodology extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'sample_size' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }
}
