<?php

use App\Http\Controllers\Admin\Category\CategoryAssignmentController;
use App\Http\Controllers\Admin\Category\CategoryController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Category Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/categories')
    ->name('admin.categories.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::controller(CategoryController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{category}/edit', 'edit')
                ->name('edit');

            Route::put('/{category}', 'update')
                ->name('update');

            Route::delete('/{category}', 'destroy')
                ->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Category Assignments
        |--------------------------------------------------------------------------
        */

        Route::prefix('{type}/{id}')
            ->name('assignment.')
            ->controller(CategoryAssignmentController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::put('/', 'update')
                    ->name('update');
            });
    });