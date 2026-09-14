<?php

namespace App\Actions\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class UpdateTag
{
    public function handle(Tag $tag, array $data): Tag
    {
        return DB::transaction(function () use ($tag, $data) {
            $tag->update($data);

            return $tag->refresh();
        });
    }
}