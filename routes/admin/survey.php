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
    ->middleware('auth')
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Surveys
        |--------------------------------------------------------------------------
        */

        Route::controller(SurveyController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:surveys.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:surveys.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:surveys.create');

            Route::get('/{survey}', 'show')
                ->name('show')
                ->middleware('permission:surveys.view');

            Route::get('/{survey}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:surveys.edit');

            Route::put('/{survey}', 'update')
                ->name('update')
                ->middleware('permission:surveys.edit');

            Route::delete('/{survey}', 'destroy')
                ->name('destroy')
                ->middleware('permission:surveys.delete');

            Route::get('/{survey}/analytics', 'analytics')
                ->name('analytics')
                ->middleware('permission:surveys.analytics');
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
                    ->name('index')
                    ->middleware('permission:surveys.sections.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:surveys.sections.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:surveys.sections.create');

                Route::get('/{section}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:surveys.sections.edit');

                Route::put('/{section}', 'update')
                    ->name('update')
                    ->middleware('permission:surveys.sections.edit');

                Route::delete('/{section}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:surveys.sections.delete');
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
                    ->name('index')
                    ->middleware('permission:surveys.questions.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:surveys.questions.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:surveys.questions.create');

                Route::get('/{question}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:surveys.questions.edit');

                Route::put('/{question}', 'update')
                    ->name('update')
                    ->middleware('permission:surveys.questions.edit');

                Route::delete('/{question}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:surveys.questions.delete');
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
                    ->name('index')
                    ->middleware('permission:surveys.options.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:surveys.options.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:surveys.options.create');

                Route::get('/{option}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:surveys.options.edit');

                Route::put('/{option}', 'update')
                    ->name('update')
                    ->middleware('permission:surveys.options.edit');

                Route::delete('/{option}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:surveys.options.delete');
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
                    ->name('index')
                    ->middleware('permission:surveys.responses.view');

                Route::get('/{response}', 'show')
                    ->name('show')
                    ->middleware('permission:surveys.responses.view');

                Route::delete('/{response}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:surveys.responses.delete');
            });
    });
