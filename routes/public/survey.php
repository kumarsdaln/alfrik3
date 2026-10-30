<?php

use App\Http\Controllers\Public\Survey\SurveyController;
use Illuminate\Support\Facades\Route;

Route::prefix('surveys')
    ->name('surveys.')
    ->controller(SurveyController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/{survey:slug}', 'show')
            ->name('show');

        Route::post('/{survey:slug}/submit', 'submit')
            ->name('submit');

        Route::get('/{survey:slug}/thank-you', 'thankYou')
            ->name('thank-you');
    });
