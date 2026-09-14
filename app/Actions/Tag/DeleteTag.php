<?php

namespace App\Actions\Tag;

use App\Models\Tag;
use Illuminate\Support\Facades\DB;

class DeleteTag
{
    public function handle(Tag $tag): void
    {
        DB::transaction(
            fn () => $tag->delete()
        );
    }
}