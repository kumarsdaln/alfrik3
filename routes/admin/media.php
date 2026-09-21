<?php

use App\Http\Controllers\Admin\Media\MediaController;
use Illuminate\Support\Facades\Route;


Route::prefix('admin/media/{type}/{id}')
    ->name('admin.media.')
    ->middleware(['auth', 'role:admin'])
    ->controller(MediaController::class)
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/', 'store')->name('store');
        Route::get('/{mediaId}/edit', 'edit')->name('edit');
        Route::put('/{mediaId}', 'update')->name('update');
        Route::delete('/{mediaId}', 'destroy')->name('destroy');
    });
