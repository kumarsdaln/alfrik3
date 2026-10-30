<?php

use App\Http\Controllers\Admin\Media\MediaController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Media Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/media')
    ->name('admin.media.')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Entity Media
        |--------------------------------------------------------------------------
        */

        Route::prefix('{type}/{id}')
            ->controller(MediaController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:media.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:media.create');

                Route::get('/{media}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:media.edit');

                Route::put('/{media}', 'update')
                    ->name('update')
                    ->middleware('permission:media.edit');

                Route::delete('/{media}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:media.delete');
            });
    });