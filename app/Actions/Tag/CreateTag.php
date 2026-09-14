<?php

namespace App\Actions\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class CreateTag
{
    public function handle(array $data): Tag
    {
        return DB::transaction(
            fn () => Tag::create($data)
        );
    }
}