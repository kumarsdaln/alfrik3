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
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | SEO Management
        |--------------------------------------------------------------------------
        */

        Route::prefix('{type}/{id}')
            ->controller(SeoController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:seo.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:seo.create');

                Route::delete('/{seo}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:seo.delete');
            });
    });