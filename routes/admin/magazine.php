<?php

use App\Http\Controllers\Admin\Magazine\MagazineArticleController;
use App\Http\Controllers\Admin\Magazine\MagazineController;
use App\Http\Controllers\Admin\Magazine\MagazineIssueController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Magazine Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/magazines')
    ->name('admin.magazines.')
    ->middleware(['auth', 'role:admin'])
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Magazines
        |--------------------------------------------------------------------------
        */

        Route::controller(MagazineController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{magazine}/edit', 'edit')
                ->name('edit');

            Route::put('/{magazine}', 'update')
                ->name('update');

            Route::delete('/{magazine}', 'destroy')
                ->name('destroy');

            Route::patch('/{magazine}/publish', 'publish')
                ->name('publish');

            Route::patch('/{magazine}/archive', 'archive')
                ->name('archive');
        });

        /*
        |--------------------------------------------------------------------------
        | Magazine Issues
        |--------------------------------------------------------------------------
        */

        Route::prefix('{magazine}/issues')
            ->name('issues.')
            ->controller(MagazineIssueController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{issue}/edit', 'edit')
                    ->name('edit');

                Route::put('/{issue}', 'update')
                    ->name('update');

                Route::delete('/{issue}', 'destroy')
                    ->name('destroy');

                Route::patch('/{issue}/publish', 'publish')
                    ->name('publish');

                Route::patch('/{issue}/archive', 'archive')
                    ->name('archive');
            });

        /*
        |--------------------------------------------------------------------------
        | Magazine Articles
        |--------------------------------------------------------------------------
        */

        Route::prefix('{magazine}/issues/{issue}/articles')
            ->name('issues.articles.')
            ->controller(MagazineArticleController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{article}/edit', 'edit')
                    ->name('edit');

                Route::put('/{article}', 'update')
                    ->name('update');

                Route::delete('/{article}', 'destroy')
                    ->name('destroy');

                Route::patch('/{article}/publish', 'publish')
                    ->name('publish');

                Route::patch('/{article}/archive', 'archive')
                    ->name('archive');
            });
    });
