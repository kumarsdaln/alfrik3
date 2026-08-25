<?php

use App\Http\Controllers\Admin\Survey\SurveyController;
use Illuminate\Support\Facades\Route;

// Admin survey builder + results
Route::name('admin.surveys.')->prefix('admin/surveys')->middleware(['auth', 'role:admin'])->group(function () {
    Route::get('', [SurveyController::class, 'index'])->name('index');
    Route::get('create', [SurveyController::class, 'create'])->name('create');
    Route::post('store', [SurveyController::class, 'store'])->name('store');
    Route::get('{survey}/edit', [SurveyController::class, 'edit'])->name('edit');
    Route::post('{survey}/update', [SurveyController::class, 'update'])->name('update');
    Route::patch('{survey}/status', [SurveyController::class, 'updateStatus'])->name('update.status');
    Route::get('{survey}/results', [SurveyController::class, 'results'])->name('results');
    Route::delete('{survey}/delete', [SurveyController::class, 'destroy'])->name('destroy');
});
