<?php

use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\ReportSectionController;
use App\Http\Controllers\Admin\ReportContentBlockController;
use Illuminate\Support\Facades\Route;

// Admin report management
Route::prefix('admin/report')
    ->name('admin.report.')
    ->middleware(['auth', 'role:admin'])
    ->controller(ReportController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/create', 'create')
            ->name('create');

        Route::post('/', 'store')
            ->name('store');

        Route::get('/{report}', 'show')
            ->name('show');

        Route::get('/{report}/edit', 'edit')
            ->name('edit');

        Route::put('/{report}', 'update')
            ->name('update');

        Route::delete('/{report}', 'destroy')
            ->name('destroy');

        Route::prefix('{report}/sections')
            ->name('sections.')
            ->controller(ReportSectionController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{section}/edit', 'edit')->name('edit');
                Route::put('/{section}', 'update')->name('update');
                Route::delete('/{section}', 'destroy')->name('destroy');

                Route::prefix('{section}/blocks')
                    ->name('blocks.')
                    ->controller(ReportContentBlockController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');
                        Route::get('/create', 'create')->name('create');
                        Route::post('/', 'store')->name('store');
                        Route::get('/{block}/edit', 'edit')->name('edit');
                        Route::put('/{block}', 'update')->name('update');
                        Route::delete('/{block}', 'destroy')->name('destroy');
                    });
            });
    });
