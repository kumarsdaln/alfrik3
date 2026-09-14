<?php

namespace App\Actions\Category;

use App\Models\Category;
use Illuminate\Support\Facades\DB;

class CreateCategory
{
    public function handle(array $data): Category
    {
        return DB::transaction(
            fn () => Category::create($data)
        );
    }
}