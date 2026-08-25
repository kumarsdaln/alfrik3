<?php

use App\Http\Controllers\Admin\Report\ReportCategoryController;
use App\Http\Controllers\Admin\Report\ReportController;
use Illuminate\Support\Facades\Route;

// Admin report management
Route::name('admin.reports.')->prefix('admin/reports')->middleware(['auth', 'role:admin'])->group(function () {

    Route::name('categories.')->prefix('categories')->group(function () {
        Route::get('', [ReportCategoryController::class, 'index'])->name('index');
        Route::get('create', [ReportCategoryController::class, 'create'])->name('create');
        Route::post('store', [ReportCategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [ReportCategoryController::class, 'edit'])->name('edit');
        Route::post('{category}/update', [ReportCategoryController::class, 'update'])->name('update');
        Route::delete('{category}/delete', [ReportCategoryController::class, 'destroy'])->name('destroy');
    });

    Route::get('', [ReportController::class, 'index'])->name('index');
    Route::get('create', [ReportController::class, 'create'])->name('create');
    Route::post('store', [ReportController::class, 'store'])->name('store');
    Route::get('{report}/edit', [ReportController::class, 'edit'])->name('edit');
    Route::post('{report}/update', [ReportController::class, 'update'])->name('update');
    Route::patch('{report}/status', [ReportController::class, 'updateStatus'])->name('update.status');
    Route::delete('{report}/delete', [ReportController::class, 'destroy'])->name('destroy');
});
