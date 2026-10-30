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
    ->middleware('auth')
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Magazines
        |--------------------------------------------------------------------------
        */

        Route::controller(MagazineController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:magazines.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:magazines.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:magazines.create');

            Route::get('/{magazine}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:magazines.edit');

            Route::put('/{magazine}', 'update')
                ->name('update')
                ->middleware('permission:magazines.edit');

            Route::delete('/{magazine}', 'destroy')
                ->name('destroy')
                ->middleware('permission:magazines.delete');

            Route::patch('/{magazine}/publish', 'publish')
                ->name('publish')
                ->middleware('permission:magazines.publish');

            Route::patch('/{magazine}/archive', 'archive')
                ->name('archive')
                ->middleware('permission:magazines.archive');
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
                    ->name('index')
                    ->middleware('permission:magazines.issues.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:magazines.issues.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:magazines.issues.create');

                Route::get('/{issue}', 'show')
                    ->name('show')
                    ->middleware('permission:magazines.issues.view');

                Route::get('/{issue}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:magazines.issues.edit');

                Route::put('/{issue}', 'update')
                    ->name('update')
                    ->middleware('permission:magazines.issues.edit');

                Route::delete('/{issue}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:magazines.issues.delete');

                Route::patch('/{issue}/publish', 'publish')
                    ->name('publish')
                    ->middleware('permission:magazines.issues.publish');

                Route::patch('/{issue}/archive', 'archive')
                    ->name('archive')
                    ->middleware('permission:magazines.issues.archive');
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
                    ->name('index')
                    ->middleware('permission:magazines.articles.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:magazines.articles.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:magazines.articles.create');

                Route::get('/{article}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:magazines.articles.edit');

                Route::get('/{article}', 'show')
                    ->name('show')
                    ->middleware('permission:magazines.articles.show');

                Route::put('/{article}', 'update')
                    ->name('update')
                    ->middleware('permission:magazines.articles.edit');

                Route::delete('/{article}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:magazines.articles.delete');

                Route::patch('/{article}/publish', 'publish')
                    ->name('publish')
                    ->middleware('permission:magazines.articles.publish');

                Route::patch('/{article}/archive', 'archive')
                    ->name('archive')
                    ->middleware('permission:magazines.articles.archive');
            });
    });
