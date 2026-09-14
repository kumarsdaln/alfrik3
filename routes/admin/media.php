<?php

use App\Http\Controllers\Admin\Media\MediaController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/media')
    ->name('admin.media.')
    ->middleware(['auth', 'role:admin'])
    ->controller(MediaController::class)
    ->group(function () {
        Route::get('/{media}/edit', 'edit')->name('edit');

        Route::put('/{media}', 'update')->name('update');

        Route::delete('/{media}', 'destroy')->name('destroy');
    });