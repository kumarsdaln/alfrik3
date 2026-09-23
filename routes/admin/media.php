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
    ->middleware(['auth', 'role:admin'])
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
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{media}/edit', 'edit')
                    ->name('edit');

                Route::put('/{media}', 'update')
                    ->name('update');

                Route::delete('/{media}', 'destroy')
                    ->name('destroy');
            });
    });