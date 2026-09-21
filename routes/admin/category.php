<?php

use App\Http\Controllers\Admin\Category\CategoryAssignmentController;
use App\Http\Controllers\Admin\Category\CategoryController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/categories')
    ->name('admin.categories.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {
        Route::controller(CategoryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('/{category}/edit', 'edit')->name('edit');
            Route::put('/{category}', 'update')->name('update');
            Route::delete('/{category}', 'destroy')->name('destroy');
        });


        Route::prefix('{type}/{id}')
            ->name('assignment.')
            ->controller(CategoryAssignmentController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::put('/', 'update')->name('update');
            });
    });
