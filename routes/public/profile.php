<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\Profile\ProfileController;

Route::prefix('profiles')->group(function () {
    Route::get('/', [ProfileController::class, 'index'])
        ->name('profiles.index');

    Route::get('/{username}', [ProfileController::class, 'show'])
        ->name('profile.show');
});