<?php

use App\Http\Controllers\Admin\Tag\TagController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/tags')
    ->name('admin.tags.')
    ->middleware(['auth', 'role:admin'])
    ->controller(TagController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{tag}/edit', 'edit')->name('edit');
        Route::put('/{tag}', 'update')->name('update');
        Route::delete('/{tag}', 'destroy')->name('destroy');
    });
