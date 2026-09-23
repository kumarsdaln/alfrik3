<?php

use App\Http\Controllers\Admin\Survey\SurveyController;
use App\Http\Controllers\Admin\Survey\SurveyQuestionController;
use App\Http\Controllers\Admin\Survey\SurveyQuestionOptionController;
use App\Http\Controllers\Admin\Survey\SurveyResponseController;
use App\Http\Controllers\Admin\Survey\SurveySectionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Survey Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/survey')
    ->name('admin.survey.')
    ->middleware(['auth', 'role:admin'])
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Surveys
        |--------------------------------------------------------------------------
        */

        Route::controller(SurveyController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{survey}', 'show')
                ->name('show');

            Route::get('/{survey}/edit', 'edit')
                ->name('edit');

            Route::put('/{survey}', 'update')
                ->name('update');

            Route::delete('/{survey}', 'destroy')
                ->name('destroy');

            Route::get('/{survey}/analytics', 'analytics')
                ->name('analytics');
        });

        /*
        |--------------------------------------------------------------------------
        | Survey Sections
        |--------------------------------------------------------------------------
        */

        Route::prefix('{survey}/sections')
            ->name('sections.')
            ->controller(SurveySectionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{section}/edit', 'edit')
                    ->name('edit');

                Route::put('/{section}', 'update')
                    ->name('update');

                Route::delete('/{section}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Survey Questions
        |--------------------------------------------------------------------------
        */

        Route::prefix('{survey}/questions')
            ->name('questions.')
            ->controller(SurveyQuestionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{question}/edit', 'edit')
                    ->name('edit');

                Route::put('/{question}', 'update')
                    ->name('update');

                Route::delete('/{question}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Survey Question Options
        |--------------------------------------------------------------------------
        */

        Route::prefix('{survey}/questions/{question}/options')
            ->name('questions.options.')
            ->controller(SurveyQuestionOptionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{option}/edit', 'edit')
                    ->name('edit');

                Route::put('/{option}', 'update')
                    ->name('update');

                Route::delete('/{option}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Survey Responses
        |--------------------------------------------------------------------------
        */

        Route::prefix('{survey}/responses')
            ->name('responses.')
            ->controller(SurveyResponseController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/{response}', 'show')
                    ->name('show');

                Route::delete('/{response}', 'destroy')
                    ->name('destroy');
            });
    });