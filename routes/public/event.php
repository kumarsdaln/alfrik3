<?php

use App\Http\Controllers\Public\Events\EventController;
use Illuminate\Support\Facades\Route;

Route::name('events.')->prefix('events')->group(function () {
    Route::get('', [EventController::class, 'index'])->name('index');

    // Auth-only registration toggle, registered before the slug route so
    // /events/{slug}/register resolves to the action, not the detail page.
    Route::post('{event:slug}/register', [EventController::class, 'register'])
        ->middleware('auth')->name('register');

    Route::get('{event:slug}', [EventController::class, 'show'])->name('show');
});
