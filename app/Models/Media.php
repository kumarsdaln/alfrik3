<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

#[Fillable([
    'collection',
    'name',
    'file_name',
    'mime_type',
    'extension',
    'size',
    'disk',
    'path',
    'alt',
    'metadata',
])]
class Media extends Model
{
    protected function casts(): array
    {
        return [
            'size' => 'integer',
            'metadata' => 'array',
        ];
    }

    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }
}
