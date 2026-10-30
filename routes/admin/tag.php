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
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Tags
        |--------------------------------------------------------------------------
        */

        Route::controller(TagController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:tags.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:tags.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:tags.create');

            Route::get('/{tag}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:tags.edit');

            Route::put('/{tag}', 'update')
                ->name('update')
                ->middleware('permission:tags.edit');

            Route::delete('/{tag}', 'destroy')
                ->name('destroy')
                ->middleware('permission:tags.delete');
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
                    ->name('index')
                    ->middleware('permission:tags.assign');

                Route::put('/', 'update')
                    ->name('update')
                    ->middleware('permission:tags.assign');
            });
    });