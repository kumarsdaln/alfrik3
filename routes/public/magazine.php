<?php

use App\Http\Controllers\Public\Magazine\MagazineController;
use Illuminate\Support\Facades\Route;

Route::prefix('magazine/')->name('magazine.')->group(function () {
    Route::get('', [MagazineController::class, 'index'])->name('index');
    Route::get('{category}/{magazine:slug}', [MagazineController::class, 'view'])->name('view');
});