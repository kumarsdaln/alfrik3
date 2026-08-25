<?php

use App\Http\Controllers\Public\Research\ResearchController;
use Illuminate\Support\Facades\Route;

// Public research library
Route::name('research.')->prefix('research')->group(function () {
    Route::get('', [ResearchController::class, 'index'])->name('index');
    Route::get('{paper:slug}', [ResearchController::class, 'show'])->name('show');
    Route::get('{paper:slug}/download', [ResearchController::class, 'download'])->name('download');
});
