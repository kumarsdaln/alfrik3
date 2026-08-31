<?php

use App\Http\Controllers\Settings\ProfileController;
use App\Http\Controllers\Settings\SecurityController;
use App\Http\Controllers\Settings\CountryController;
use App\Http\Controllers\Settings\HeadlineController;
use App\Http\Controllers\Settings\LanguageController;
use App\Http\Controllers\Settings\IndustryController;
use App\Http\Controllers\Settings\PositionController;
use App\Http\Controllers\Settings\UsernameController;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', '/settings/profile');

    Route::prefix('settings')->group(function () {
        Route::get('profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('profile', [ProfileController::class, 'update'])->name('profile.update');

        Route::get('country', [CountryController::class, 'edit'])
            ->name('settings.country.edit');

        Route::put('country', [CountryController::class, 'update'])
            ->name('settings.country.update');

        Route::get('languages', [LanguageController::class, 'edit'])
            ->name('settings.languages.edit');

        Route::put('languages', [LanguageController::class, 'update'])
            ->name('settings.languages.update');

        Route::get('industries', [IndustryController::class, 'edit'])
            ->name('settings.industries.edit');

        Route::put('industries', [IndustryController::class, 'update'])
            ->name('settings.industries.update');

        Route::get('position', [PositionController::class, 'edit'])
            ->name('settings.position.edit');

        Route::put('position', [PositionController::class, 'update'])
            ->name('settings.position.update');

        Route::get('username', [UsernameController::class, 'edit'])
            ->name('settings.username.edit');

        Route::put('username', [UsernameController::class, 'update'])
            ->name('settings.username.update');

        Route::get('headline', [HeadlineController::class, 'edit'])
            ->name('settings.headline.edit');

        Route::put('headline', [HeadlineController::class, 'update'])
            ->name('settings.headline.update');
    });
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::delete('settings/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('settings/security', [SecurityController::class, 'edit'])
        ->middleware(RequirePassword::class)
        ->name('security.edit');

    Route::put('settings/password', [SecurityController::class, 'update'])
        ->middleware('throttle:6,1')
        ->name('user-password.update');

    Route::inertia('settings/appearance', 'settings/Appearance')->name('appearance.edit');
});

Route::get('.well-known/passkey-endpoints', function () {
    return response()->json([
        'enroll' => route('security.edit'),
        'manage' => route('security.edit'),
    ]);
})->name('well-known.passkeys');
