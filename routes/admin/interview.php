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
    ->middleware('auth')
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Interviews
        |--------------------------------------------------------------------------
        */

        Route::controller(InterviewController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:interviews.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:interviews.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:interviews.create');

            Route::get('/{interview}', 'show')
                ->name('show')
                ->middleware('permission:interviews.view');

            Route::get('/{interview}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:interviews.edit');

            Route::put('/{interview}', 'update')
                ->name('update')
                ->middleware('permission:interviews.edit');

            Route::delete('/{interview}', 'destroy')
                ->name('destroy')
                ->middleware('permission:interviews.delete');
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
                    ->name('index')
                    ->middleware('permission:interviews.participants.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:interviews.participants.create');

                Route::delete('/{participant}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:interviews.participants.delete');
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
                    ->name('index')
                    ->middleware('permission:interviews.questions.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:interviews.questions.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:interviews.questions.create');

                Route::put('/reorder', 'reorder')
                    ->name('reorder')
                    ->middleware('permission:interviews.questions.edit');

                Route::get('/{question}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:interviews.questions.edit');

                Route::put('/{question}', 'update')
                    ->name('update')
                    ->middleware('permission:interviews.questions.edit');

                Route::delete('/{question}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:interviews.questions.delete');
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
                    ->name('create')
                    ->middleware('permission:interviews.answers.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:interviews.answers.create');

                Route::get('/{answer}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:interviews.answers.edit');

                Route::put('/{answer}', 'update')
                    ->name('update')
                    ->middleware('permission:interviews.answers.edit');

                Route::delete('/{answer}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:interviews.answers.delete');
            });
    });