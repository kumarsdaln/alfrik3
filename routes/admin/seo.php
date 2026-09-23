<?php

use App\Http\Controllers\Admin\Seo\SeoController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin SEO Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/seo')
    ->name('admin.seo.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | SEO Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('{type}/{id}')
            ->name('resource.')
            ->controller(SeoController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::delete('/{seo}', 'destroy')
                    ->name('destroy');
            });
    });