<?php

namespace App\Actions\Tag;

use Illuminate\Database\Eloquent\Model;

class SyncTags
{
    public function handle(
        Model $model,
        array $tagIds,
    ): void {
        abort_unless(
            method_exists($model, 'tags'),
            404
        );

        $model->tags()->sync($tagIds);
    }
}
