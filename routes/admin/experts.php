<?php

use App\Http\Controllers\Expert\AvailabilityController;
use App\Http\Controllers\Expert\DispatchController;
use App\Http\Controllers\Expert\FollowerController;
use App\Http\Controllers\Expert\PortfolioController;
use App\Http\Controllers\Expert\ProfileController;
use App\Http\Controllers\Expert\ServiceController;
use App\Http\Controllers\Expert\SlotBookingController;
use Illuminate\Support\Facades\Route;

// `expert.context` shares the viewed expert as a shared Inertia prop
// (expertContext.profile) that ExpertProfileLayout — header + section tabs —
// depends on. Applied to the whole group so EVERY expert page has it and the
// tab links never lose the external_id (which produced /experts//… URLs).
Route::prefix('/experts')->name('experts.')->middleware('expert.context')->group(function () {
    Route::post('{expert:external_id}/follow', [FollowerController::class, 'toggleFollow'])->name('follow')->middleware('auth');
    Route::get('', [ProfileController::class, 'index'])->name('index');
    Route::get('{expert:external_id}', [ProfileController::class, 'show'])
        ->name('profile');
    Route::get('{expert:external_id}/media-kit', [ProfileController::class, 'mediaKit'])
        ->name('media-kit');
    Route::get('{expert:external_id}/portfolios', [PortfolioController::class, 'index'])
        ->name('portfolio');
    Route::get('{expert:external_id}/dispatches', [DispatchController::class, 'index'])
        ->name('dispatches');
    Route::get('{expert:external_id}/portfolios/{portfolio:id}', [PortfolioController::class, 'show'])
        ->name('portfolio.show')->withoutScopedBindings();
    Route::get('{expert:external_id}/services', [ServiceController::class, 'index'])
        ->name('services');
    Route::get('{expert:external_id}/services/{service:id}', [ServiceController::class, 'show'])
        ->name('services.show')->withoutScopedBindings();
    Route::get('{expert:external_id}/availability', [AvailabilityController::class, 'index'])
        ->name('availability');
    Route::get('{expert:external_id}/availability/month', [AvailabilityController::class, 'month'])
        ->name('availability.month');
    Route::get('{expert:external_id}/availability/date', [AvailabilityController::class, 'date'])
        ->name('availability.date');
    Route::post('{expert:external_id}/availability/slots/booking', [SlotBookingController::class, 'store'])
        ->name('availability.slots.booking');
});

// links
Route::domain('links.'.parse_url(config('app.url'), PHP_URL_HOST))->group(function () {
    Route::get('{expert:external_id}', [ProfileController::class, 'bioLink'])
        ->name('experts.biolink');
});
