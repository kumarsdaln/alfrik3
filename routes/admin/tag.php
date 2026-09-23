<?php

use App\Http\Controllers\Admin\Tag\TagAssignmentController;
use App\Http\Controllers\Admin\Tag\TagController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Tag Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/tags')
    ->name('admin.tags.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        Route::controller(TagController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{tag}/edit', 'edit')
                ->name('edit');

            Route::put('/{tag}', 'update')
                ->name('update');

            Route::delete('/{tag}', 'destroy')
                ->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Tag Assignments
        |--------------------------------------------------------------------------
        */

        Route::prefix('{type}/{id}')
            ->name('assignment.')
            ->controller(TagAssignmentController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::put('/', 'update')
                    ->name('update');
            });
    });