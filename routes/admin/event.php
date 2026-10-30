<?php

use App\Http\Controllers\Admin\Event\EventController;
use App\Http\Controllers\Admin\Event\EventRegistrationController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/events')
    ->name('admin.events.')
    ->middleware(['auth'])
    ->group(function () {
        /*
        |--------------------------------------------------------------------------
        | Event Listing and Creation
        |--------------------------------------------------------------------------
        */

        Route::get('/', [EventController::class, 'index'])
            ->name('index')
            ->middleware('permission:events.index');

        Route::get('/create', [EventController::class, 'create'])
            ->name('create')
            ->middleware('permission:events.create');

        Route::post('/', [EventController::class, 'store'])
            ->name('store')
            ->middleware('permission:events.create');

        /*
        |--------------------------------------------------------------------------
        | Event Registrations
        |--------------------------------------------------------------------------
        */

        Route::prefix('registrations')
            ->name('registrations.')
            ->group(function () {
                Route::get('/scanner', [
                    EventRegistrationController::class,
                    'scanner',
                ])
                    ->name('scanner')
                    ->middleware('permission:events.registrations.view');

                Route::post('/scan', [
                    EventRegistrationController::class,
                    'scan',
                ])
                    ->name('scan')
                    ->middleware('permission:events.registrations.checkin');
            });

        /*
        |--------------------------------------------------------------------------
        | Event Editing
        |--------------------------------------------------------------------------
        */

        Route::get('/{event}/edit', [EventController::class, 'edit'])
            ->name('edit')
            ->middleware('permission:events.update');

        Route::put('/{event}', [EventController::class, 'update'])
            ->name('update')
            ->middleware('permission:events.update');

        Route::delete('/{event}', [EventController::class, 'destroy'])
            ->name('destroy')
            ->middleware('permission:events.delete');

        /*
        |--------------------------------------------------------------------------
        | Event Details
        |--------------------------------------------------------------------------
        */

        Route::get('/{event}', [EventController::class, 'show'])
            ->name('show')
            ->middleware('permission:events.view');
    });
