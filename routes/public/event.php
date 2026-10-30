
<?php

use App\Http\Controllers\Public\Event\EventController;
use App\Http\Controllers\Public\Event\EventRegistrationController;
use App\Http\Controllers\Public\Event\EventReviewController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Events
|--------------------------------------------------------------------------
*/

Route::prefix('events')
    ->name('events.')
    ->group(function () {
        Route::get('/', [
            EventController::class,
            'index',
        ])->name('index');

        Route::get('/{event:slug}', [
            EventController::class,
            'show',
        ])->name('show');
    });

/*
|--------------------------------------------------------------------------
| Authenticated Event Registration and Reviews
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Event Registration
    |--------------------------------------------------------------------------
    */

    Route::prefix('events/{event:slug}')
        ->name('events.')
        ->group(function () {
            Route::get('/register', [
                EventRegistrationController::class,
                'create',
            ])->name('register');

            Route::post('/register', [
                EventRegistrationController::class,
                'store',
            ])->name('registrations.store');

            Route::post('/reviews', [
                EventReviewController::class,
                'store',
            ])->name('reviews.store');
        });

    /*
    |--------------------------------------------------------------------------
    | Manage an Existing Registration
    |--------------------------------------------------------------------------
    */

    Route::prefix('event-registrations/{registration}')
        ->name('events.registrations.')
        ->group(function () {
            Route::get('/', [
                EventRegistrationController::class,
                'show',
            ])->name('show');

            Route::post('/cancel', [
                EventRegistrationController::class,
                'cancel',
            ])->name('cancel');
        });

    /*
    |--------------------------------------------------------------------------
    | Manage an Existing Review
    |--------------------------------------------------------------------------
    */

    Route::prefix('event-reviews/{review}')
        ->name('events.reviews.')
        ->group(function () {
            Route::put('/', [
                EventReviewController::class,
                'update',
            ])->name('update');

            Route::delete('/', [
                EventReviewController::class,
                'destroy',
            ])->name('destroy');
        });
});
