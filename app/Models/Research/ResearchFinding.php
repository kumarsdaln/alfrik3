<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchFindingType;
use App\Models\Research\Research;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'research_id',
    'title',
    'summary',
    'description',
    'type',
    'confidence',
    'position',
])]
class ResearchFinding extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => ResearchFindingType::class,
            'confidence' => 'decimal:2',
            'position' => 'integer',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function evidence(): HasMany
    {
        return $this->hasMany(ResearchEvidence::class)
            ->orderBy('position');
    }
}
