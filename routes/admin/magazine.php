<?php

use App\Http\Controllers\Admin\Magazine\MagazineCategoryController;
use App\Http\Controllers\Admin\Magazine\MagazineController;
use Illuminate\Support\Facades\Route;


// Admin magazine management
Route::name('admin.magazine.')->prefix('admin/magazine')->middleware(['auth', 'role:admin'])->group(function () {

    // Categories — registered before {magazine} routes.
    Route::name('categories.')->prefix('categories')->group(function () {
        Route::get('', [MagazineCategoryController::class, 'index'])->name('index');
        Route::get('create', [MagazineCategoryController::class, 'create'])->name('create');
        Route::post('store', [MagazineCategoryController::class, 'store'])->name('store');
        Route::get('{category}/edit', [MagazineCategoryController::class, 'edit'])->name('edit');
        Route::post('{category}/update', [MagazineCategoryController::class, 'update'])->name('update');
        Route::delete('{category}/delete', [MagazineCategoryController::class, 'destroy'])->name('destroy');
    });

    Route::get('', [MagazineController::class, 'index'])->name('index');
    Route::get('create', [MagazineController::class, 'create'])->name('create');
    Route::post('store', [MagazineController::class, 'store'])->name('store');
    Route::get('{magazine}/edit', [MagazineController::class, 'edit'])->name('edit');
    Route::post('{magazine}/update', [MagazineController::class, 'update'])->name('update');
    Route::patch('{magazine}/status', [MagazineController::class, 'updateStatus'])->name('update.status');
    Route::delete('{magazine}/delete', [MagazineController::class, 'destroy'])->name('destroy');
});
