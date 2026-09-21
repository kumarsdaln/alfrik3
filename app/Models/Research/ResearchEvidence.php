<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchEvidenceType;
use App\Models\Research\ResearchFinding;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'research_id',
    'finding_id',
    'type',
    'reference_id',
    'title',
    'description',
    'citation',
    'position',
])]
class ResearchEvidence extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => ResearchEvidenceType::class,
            'reference_id' => 'integer',
            'position' => 'integer',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function finding(): BelongsTo
    {
        return $this->belongsTo(
            ResearchFinding::class,
            'finding_id'
        );
    }
}
