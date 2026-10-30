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
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Categories
        |--------------------------------------------------------------------------
        */

        Route::controller(CategoryController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:categories.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:categories.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:categories.create');

            Route::get('/{category}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:categories.edit');

            Route::put('/{category}', 'update')
                ->name('update')
                ->middleware('permission:categories.edit');

            Route::delete('/{category}', 'destroy')
                ->name('destroy')
                ->middleware('permission:categories.delete');
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
                    ->name('index')
                    ->middleware('permission:categories.assign');

                Route::put('/', 'update')
                    ->name('update')
                    ->middleware('permission:categories.assign');
            });
    });