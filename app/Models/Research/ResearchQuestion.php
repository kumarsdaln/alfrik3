<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchQuestionType;
use App\Models\Research\Research;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'research_id',
    'question',
    'description',
    'type',
    'position',
])]
class ResearchQuestion extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'type' => ResearchQuestionType::class,
            'position' => 'integer',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }
}
