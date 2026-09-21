<?php

use App\Http\Controllers\Public\Survey\SurveyController;
use Illuminate\Support\Facades\Route;


Route::prefix('surveys')
    ->name('surveys.')
    ->controller(SurveyController::class)
    ->group(function () {
        Route::get('/', 'index')
            ->name('index');

        Route::get('/{survey}', 'show')
            ->name('show');

        Route::post('/{survey}/submit', 'submit')
            ->name('submit');

        Route::get('/{survey}/thank-you', 'thankYou')
            ->name('thank-you');
    });
