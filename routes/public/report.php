<?php

use App\Http\Controllers\Public\Report\ReportController;
use Illuminate\Support\Facades\Route;

// Public reports library
Route::name('reports.')->prefix('reports')->group(function () {
    Route::get('', [ReportController::class, 'index'])->name('index');
    Route::get('{report:slug}', [ReportController::class, 'show'])->name('show');
    Route::get('{report:slug}/download', [ReportController::class, 'download'])->name('download');
});
