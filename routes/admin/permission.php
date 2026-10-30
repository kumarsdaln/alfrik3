<?php

use App\Http\Controllers\Admin\User\PermissionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/permissions')
    ->name('admin.permissions.')
    ->middleware('auth')
    ->controller(PermissionController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('index')
            ->middleware('permission:permissions.view');

        Route::get('/create', 'create')
            ->name('create')
            ->middleware('permission:permissions.create');

        Route::post('/', 'store')
            ->name('store')
            ->middleware('permission:permissions.create');

        Route::get('/{permission}', 'show')
            ->name('show')
            ->middleware('permission:permissions.view');

        Route::get('/{permission}/edit', 'edit')
            ->name('edit')
            ->middleware('permission:permissions.edit');

        Route::put('/{permission}', 'update')
            ->name('update')
            ->middleware('permission:permissions.edit');

        Route::delete('/{permission}', 'destroy')
            ->name('destroy')
            ->middleware('permission:permissions.delete');
    });