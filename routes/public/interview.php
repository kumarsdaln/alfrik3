<?php
//Public Interviews Routes
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\Interview\InterviewController;

Route::prefix('interviews')->name('interviews.')->group(function () {
    Route::get('/', [InterviewController::class, 'index'])->name('index');
    Route::get('{interview:slug}', [InterviewController::class, 'show'])->name('show');
});
