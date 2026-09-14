<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Interview\AnswerController;
use App\Http\Controllers\Admin\Interview\InterviewController;
use App\Http\Controllers\Admin\Interview\InterviewTaxonomyController;
use App\Http\Controllers\Admin\Interview\MediaController;
use App\Http\Controllers\Admin\Interview\ParticipantController;
use App\Http\Controllers\Admin\Interview\QuestionController;

Route::prefix('admin/interviews')->name('admin.interviews.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/', [InterviewController::class, 'index'])->name('index');
    Route::get('/create', [InterviewController::class, 'create'])->name('create');
    Route::post('/store', [InterviewController::class, 'store'])->name('store');
    Route::get('/{interview}', [InterviewController::class, 'show'])->name('show');
    Route::get('/{interview}/edit', [InterviewController::class, 'edit'])->name('edit');
    Route::post('/{interview}/update', [InterviewController::class, 'update'])->name('update');
    // Route::patch('/{interview}/update/status', [InterviewController::class, 'updateStatus'])->name('update.status');
    // Route::patch('/{interview}/update/type', [InterviewController::class, 'updateType'])->name('update.type');
    Route::delete('/{interview}/delete', [InterviewController::class, 'destroy'])->name('destroy');

    Route::prefix('{interview}/taxonomy')
        ->name('taxonomy.')
        ->controller(InterviewTaxonomyController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::put('/', 'update')->name('update');
        });

    Route::prefix('{interview}/participants')
        ->name('participants.')
        ->controller(ParticipantController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store');
        });
    Route::delete('participants/{participant}', [ParticipantController::class, 'destroy'])->name('participants.destroy');

    Route::prefix('{interview}/questions')
        ->name('questions.')
        ->controller(QuestionController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::put('/reorder', 'reorder')->name('reorder');
        });

    Route::prefix('questions')
        ->name('questions.')
        ->controller(QuestionController::class)
        ->group(function () {
            Route::get('/{question}/edit', 'edit')->name('edit');
            Route::put('/{question}', 'update')->name('update');
            Route::delete('/{question}', 'destroy')->name('destroy');
        });

    Route::prefix('questions/{question}/answers')
        ->name('questions.answers.')
        ->controller(AnswerController::class)
        ->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });
    Route::prefix('answers')
        ->name('questions.answers.')
        ->controller(AnswerController::class)
        ->group(function () {
            Route::get('/{answer}/edit', 'edit')->name('edit');
            Route::put('/{answer}', 'update')->name('update');
            Route::delete('/{answer}', 'destroy')->name('destroy');
        });
    // Route::get('/{interview}/questions', [QuestionController::class, 'index'])->name('questions.index');
    // Route::get('/{interview}/questions/create', [QuestionController::class, 'create'])->name('questions.create');
    // Route::post('/{interview}/questions/store', [QuestionController::class, 'store'])->name('questions.store');
    // Route::get('/{interview}/questions/{question}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    // Route::post('/{interview}/questions/{question}', [QuestionController::class, 'update'])->name('questions.update');
    // Route::delete('/{interview}/questions/{question}/delete', [QuestionController::class, 'destroy'])->name('questions.destroy');
    // Route::patch('/{interview}/questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');

    // Route::get('/{interview}/questions/{question}/answers/create', [AnswerController::class, 'create'])->name('questions.answers.create');
    // Route::post('/{interview}/questions/{question}/answers/store', [AnswerController::class, 'store'])->name('questions.answers.store');
    // Route::get('/{interview}/questions/{question}/answers/{answer}/edit', [AnswerController::class, 'edit'])->name('questions.answers.edit');
    // Route::post('/{interview}/questions/{question}/answers/{answer}', [AnswerController::class, 'update'])->name('questions.answers.update');
    // Route::delete('/{interview}/questions/{question}/answers/{answer}', [AnswerController::class, 'destroy'])->name('questions.answers.destroy');
    // Route::post('/{interview}/media', [MediaController::class, 'store'])->name('media.store');
    // Route::delete('/{interview}/media/{medium}', [MediaController::class, 'destroy'])->name('media.destroy');
});
