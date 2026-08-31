<?php

use App\Http\Controllers\Admin\Event\EventController;
use Illuminate\Support\Facades\Route;



Route::prefix('admin/events')
    ->name('admin.events.')
    ->middleware(['auth', 'role:admin'])
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Events
        |--------------------------------------------------------------------------
        */

        Route::get('/', [EventController::class, 'index'])
            ->name('index');

        Route::get('/create', [EventController::class, 'create'])
            ->name('create');

        Route::post('/', [EventController::class, 'store'])
            ->name('store');

        Route::get('/{event}/edit', [EventController::class, 'edit'])
            ->name('edit');

        Route::put('/{event}', [EventController::class, 'update'])
            ->name('update');

        Route::delete('/{event}', [EventController::class, 'destroy'])
            ->name('destroy');

        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        Route::get('/{event}', [EventController::class, 'show'])
            ->name('show');
    });