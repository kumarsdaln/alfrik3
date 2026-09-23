<?php

use App\Http\Controllers\Admin\Interview\AnswerController;
use App\Http\Controllers\Admin\Interview\InterviewController;
use App\Http\Controllers\Admin\Interview\ParticipantController;
use App\Http\Controllers\Admin\Interview\QuestionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Interview Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/interviews')
    ->name('admin.interviews.')
    ->middleware(['auth', 'role:admin'])
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Interviews
        |--------------------------------------------------------------------------
        */

        Route::controller(InterviewController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index');

            Route::get('/create', 'create')
                ->name('create');

            Route::post('/', 'store')
                ->name('store');

            Route::get('/{interview}', 'show')
                ->name('show');

            Route::get('/{interview}/edit', 'edit')
                ->name('edit');

            Route::put('/{interview}', 'update')
                ->name('update');

            Route::delete('/{interview}', 'destroy')
                ->name('destroy');
        });

        /*
        |--------------------------------------------------------------------------
        | Interview Participants
        |--------------------------------------------------------------------------
        */

        Route::prefix('{interview}/participants')
            ->name('participants.')
            ->controller(ParticipantController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::delete('/{participant}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Interview Questions
        |--------------------------------------------------------------------------
        */

        Route::prefix('{interview}/questions')
            ->name('questions.')
            ->controller(QuestionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::put('/reorder', 'reorder')
                    ->name('reorder');

                Route::get('/{question}/edit', 'edit')
                    ->name('edit');

                Route::put('/{question}', 'update')
                    ->name('update');

                Route::delete('/{question}', 'destroy')
                    ->name('destroy');
            });

        /*
        |--------------------------------------------------------------------------
        | Question Answers
        |--------------------------------------------------------------------------
        */

        Route::prefix('{interview}/questions/{question}/answers')
            ->name('questions.answers.')
            ->controller(AnswerController::class)
            ->group(function () {

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{answer}/edit', 'edit')
                    ->name('edit');

                Route::put('/{answer}', 'update')
                    ->name('update');

                Route::delete('/{answer}', 'destroy')
                    ->name('destroy');
            });
    });