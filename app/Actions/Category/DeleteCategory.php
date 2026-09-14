<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DeleteCategory
{
    public function handle(Category $category): void
    {
        DB::transaction(
            fn () => $category->delete()
        );
    }
}