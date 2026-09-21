<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchSourceType;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'research_id',
    'title',
    'source_type',
    'author',
    'publisher',
    'url',
    'published_at',
    'citation',
    'description',
    'position',
])]
class ResearchSource extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'source_type' => ResearchSourceType::class,
            'published_at' => 'date',
            'position' => 'integer',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }
}
