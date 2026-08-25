<?php

use App\Http\Controllers\Public\Survey\SurveyController;
use Illuminate\Support\Facades\Route;


// Public surveys
Route::name('surveys.')->prefix('surveys')->group(function () {
    Route::get('', [SurveyController::class, 'index'])->name('index');
    Route::get('{survey:slug}', [SurveyController::class, 'show'])->name('show');
    Route::post('{survey:slug}/submit', [SurveyController::class, 'submit'])->name('submit');
    Route::get('{survey:slug}/results', [SurveyController::class, 'results'])->name('results');
});
