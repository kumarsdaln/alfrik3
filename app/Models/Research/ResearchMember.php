<?php
namespace App\Models\Research;

use App\Enums\Research\ResearchMemberRole;
use App\Models\Research\Research;
use App\Models\User;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'research_id',
    'user_id',
    'role',
    'joined_at',
])]
class ResearchMember extends Model
{
    use HasFactory;

    protected function casts(): array
    {
        return [
            'role' => ResearchMemberRole::class,
            'joined_at' => 'datetime',
        ];
    }

    public function research(): BelongsTo
    {
        return $this->belongsTo(Research::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
