<?php

use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\ReportSectionController;
use App\Http\Controllers\Admin\Report\ReportContentBlockController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Report Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/report')
    ->name('admin.report.')
    ->middleware(['auth', 'role:admin'])
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::controller(ReportController::class)->group(function () {
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
        });

        /*
        |--------------------------------------------------------------------------
        | Report Sections
        |--------------------------------------------------------------------------
        */

        Route::prefix('{report}/sections')
            ->name('sections.')
            ->controller(ReportSectionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{section}/edit', 'edit')
                    ->name('edit');

                Route::put('/{section}', 'update')
                    ->name('update');

                Route::delete('/{section}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Report Content Blocks
        |--------------------------------------------------------------------------
        */

        Route::prefix('{report}/sections/{section}/blocks')
            ->name('sections.blocks.')
            ->controller(ReportContentBlockController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{block}/edit', 'edit')
                    ->name('edit');

                Route::put('/{block}', 'update')
                    ->name('update');

                Route::delete('/{block}', 'destroy')
                    ->name('destroy');
            });
    });
