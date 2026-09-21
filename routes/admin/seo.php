<?php

use App\Http\Controllers\Admin\Seo\SeoController;
use Illuminate\Support\Facades\Route;

Route::prefix('/admin/seo/{type}/{id}')
    ->name('admin.seo.')
    ->middleware(['auth', 'role:admin'])
    ->controller(SeoController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::delete('/{seoId}', 'destroy')->name('destroy');
    });
