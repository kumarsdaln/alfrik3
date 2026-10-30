<?php

use App\Http\Controllers\Admin\Report\ReportContentBlockController;
use App\Http\Controllers\Admin\Report\ReportController;
use App\Http\Controllers\Admin\Report\ReportSectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Report Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/report')
    ->name('admin.report.')
    ->middleware('auth')
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Reports
        |--------------------------------------------------------------------------
        */

        Route::controller(ReportController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:reports.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:reports.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:reports.create');

            Route::get('/{report}', 'show')
                ->name('show')
                ->middleware('permission:reports.view');

            Route::get('/{report}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:reports.edit');

            Route::put('/{report}', 'update')
                ->name('update')
                ->middleware('permission:reports.edit');

            Route::delete('/{report}', 'destroy')
                ->name('destroy')
                ->middleware('permission:reports.delete');
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
                    ->name('index')
                    ->middleware('permission:reports.sections.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:reports.sections.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:reports.sections.create');

                Route::get('/{section}', 'show')
                    ->name('show')
                    ->middleware('permission:reports.sections.show');

                Route::get('/{section}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:reports.sections.edit');

                Route::put('/{section}', 'update')
                    ->name('update')
                    ->middleware('permission:reports.sections.edit');

                Route::delete('/{section}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:reports.sections.delete');
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
                    ->name('index')
                    ->middleware('permission:reports.blocks.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:reports.blocks.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:reports.blocks.create');

                Route::get('/{block}', 'show')
                    ->name('show')
                    ->middleware('permission:reports.blocks.show');

                Route::get('/{block}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:reports.blocks.edit');

                Route::put('/{block}', 'update')
                    ->name('update')
                    ->middleware('permission:reports.blocks.edit');

                Route::delete('/{block}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:reports.blocks.delete');
            });
    });
