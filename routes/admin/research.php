<?php

use App\Http\Controllers\Admin\Research\ResearchAreaController;
use App\Http\Controllers\Admin\Research\ResearchController;
use Illuminate\Support\Facades\Route;

// Admin research management
Route::name('admin.research.')->prefix('admin/research')->middleware(['auth', 'role:admin'])->group(function () {

    Route::name('areas.')->prefix('areas')->group(function () {
        Route::get('', [ResearchAreaController::class, 'index'])->name('index');
        Route::get('create', [ResearchAreaController::class, 'create'])->name('create');
        Route::post('store', [ResearchAreaController::class, 'store'])->name('store');
        Route::get('{area}/edit', [ResearchAreaController::class, 'edit'])->name('edit');
        Route::post('{area}/update', [ResearchAreaController::class, 'update'])->name('update');
        Route::delete('{area}/delete', [ResearchAreaController::class, 'destroy'])->name('destroy');
    });

    Route::get('', [ResearchController::class, 'index'])->name('index');
    Route::get('create', [ResearchController::class, 'create'])->name('create');
    Route::post('store', [ResearchController::class, 'store'])->name('store');
    Route::get('{paper}/edit', [ResearchController::class, 'edit'])->name('edit');
    Route::post('{paper}/update', [ResearchController::class, 'update'])->name('update');
    Route::patch('{paper}/status', [ResearchController::class, 'updateStatus'])->name('update.status');
    Route::delete('{paper}/delete', [ResearchController::class, 'destroy'])->name('destroy');
});
