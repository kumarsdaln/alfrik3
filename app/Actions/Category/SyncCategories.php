<?php

namespace App\Actions\Category;

use Illuminate\Database\Eloquent\Model;

class SyncCategories
{
    public function handle(
        Model $model,
        array $categoryIds,
    ): void 
    {
        abort_unless(
            method_exists($model, 'categories'),
            404
        );

        $model->categories()->sync($categoryIds);
    }
}
