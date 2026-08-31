<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventMedia extends Model
{
    use HasFactory;

    protected $table = 'event_media';

    protected $fillable = [
        'event_id',
        'type',
        'title',
        'caption',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'position',
        'featured',
    ];

    protected function casts(): array
    {
        return [
            'event_id' => 'integer',
            'file_size' => 'integer',
            'position' => 'integer',
            'featured' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}