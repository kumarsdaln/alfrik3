<?php

use App\Http\Controllers\Admin\Magazine\MagazineArticleController;
use App\Http\Controllers\Admin\Magazine\MagazineArticleMediaController;
use App\Http\Controllers\Admin\Magazine\MagazineArticleSeoController;
use App\Http\Controllers\Admin\Magazine\MagazineArticleTaxonomyController;
use App\Http\Controllers\Admin\Magazine\MagazineCategoryController;
use App\Http\Controllers\Admin\Magazine\MagazineController;
use App\Http\Controllers\Admin\Magazine\MagazineIssueController;
use Illuminate\Support\Facades\Route;


// Admin magazine management
Route::prefix('admin/magazines')
    ->name('admin.magazines.')
    ->middleware(['auth', 'role:admin'])
    ->controller(MagazineController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('/{magazine}/edit', 'edit')->name('edit');
        Route::put('/{magazine}', 'update')->name('update');

        Route::delete('/{magazine}', 'destroy')->name('destroy');

        Route::patch(
            '/{magazine}/publish',
            'publish',
        )->name('publish');

        Route::patch(
            '/{magazine}/archive',
            'archive',
        )->name('archive');

        Route::scopeBindings()
            ->prefix('{magazine}/issues')
            ->name('issues.')
            ->controller(MagazineIssueController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');

                Route::get('/{issue}/edit', 'edit')->name('edit');
                Route::put('/{issue}', 'update')->name('update');
                Route::delete('/{issue}', 'destroy')->name('destroy');

                Route::patch('/{issue}/publish', 'publish')->name('publish');
                Route::patch('/{issue}/archive', 'archive')->name('archive');

                Route::scopeBindings()
                    ->prefix('{issue}/articles')
                    ->name('articles.')
                    ->controller(MagazineArticleController::class)
                    ->group(function () {
                        Route::get('/', 'index')->name('index');

                        Route::get('/create', 'create')->name('create');
                        Route::post('/', 'store')->name('store');

                        Route::get('/{article}/edit', 'edit')->name('edit');
                        Route::put('/{article}', 'update')->name('update');
                        Route::delete('/{article}', 'destroy')->name('destroy');

                        Route::patch('/{article}/publish', 'publish')->name('publish');
                        Route::patch('/{article}/archive', 'archive')->name('archive');

                        Route::scopeBindings()
                            ->prefix('{article}/media')
                            ->name('media.')
                            ->controller(MagazineArticleMediaController::class)
                            ->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::post('/', 'store')->name('store');
                                Route::put('/{media}', 'update')->name('update');
                                Route::delete('/{media}', 'destroy')->name('destroy');
                            });

                        Route::scopeBindings()
                            ->prefix('{article}/taxonomy')
                            ->name('taxonomy.')
                            ->controller(MagazineArticleTaxonomyController::class)
                            ->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::put('/', 'update')->name('update');
                            });

                        Route::scopeBindings()
                            ->prefix('{article}/seo')
                            ->name('seo.')
                            ->controller(MagazineArticleSeoController::class)
                            ->group(function () {
                                Route::get('/', 'index')->name('index');
                                Route::put('/', 'update')->name('update');
                            });
                    });
            });
    });
