<?php

use App\Http\Controllers\Admin\User\RoleController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin/roles')
    ->name('admin.roles.')
    ->middleware('auth')
    ->controller(RoleController::class)
    ->group(function () {

        Route::get('/', 'index')
            ->name('index')
            ->middleware('permission:roles.view');

        Route::get('/create', 'create')
            ->name('create')
            ->middleware('permission:roles.create');

        Route::post('/', 'store')
            ->name('store')
            ->middleware('permission:roles.create');

        Route::get('/{role}', 'show')
            ->name('show')
            ->middleware('permission:roles.view');

        Route::get('/{role}/edit', 'edit')
            ->name('edit')
            ->middleware('permission:roles.edit');

        Route::put('/{role}', 'update')
            ->name('update')
            ->middleware('permission:roles.edit');

        Route::delete('/{role}', 'destroy')
            ->name('destroy')
            ->middleware('permission:roles.delete');
    });