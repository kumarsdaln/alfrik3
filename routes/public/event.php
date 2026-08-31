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

Route::get('/events', [
    EventController::class,
    'index',
])->name('events.index');

Route::get('/events/{event:slug}', [
    EventController::class,
    'show',
])->name('events.show');


/*
|--------------------------------------------------------------------------
| Event Registration
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    Route::get('/events/{event}/register', [
        EventRegistrationController::class,
        'create',
    ])->name('events.register');

    Route::post('/events/{event}/register', [
        EventRegistrationController::class,
        'store',
    ])->name('events.registrations.store');

    Route::get('/event-registrations/{registration}', [
        EventRegistrationController::class,
        'show',
    ])->name('events.registrations.show');

    Route::post(
        '/event-registrations/{registration}/cancel',
        [
            EventRegistrationController::class,
            'cancel',
        ]
    )->name('events.registrations.cancel');


    /*
    |--------------------------------------------------------------------------
    | Reviews
    |--------------------------------------------------------------------------
    */

    Route::post('/events/{event}/reviews', [
        EventReviewController::class,
        'store',
    ])->name('events.reviews.store');

    Route::put('/event-reviews/{review}', [
        EventReviewController::class,
        'update',
    ])->name('events.reviews.update');

    Route::delete('/event-reviews/{review}', [
        EventReviewController::class,
        'destroy',
    ])->name('events.reviews.destroy');
});
