<?php

use App\Http\Controllers\Admin\User\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::prefix('users')
            ->name('users.')
            ->controller(UserController::class)
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index');

                Route::get('/create', 'create')
                    ->name('create');

                Route::post('/', 'store')
                    ->name('store');

                Route::get('/{user}', 'show')
                    ->name('show');

                Route::get('/{user}/edit', 'edit')
                    ->name('edit');

                Route::put('/{user}', 'update')
                    ->name('update');

                Route::patch('/{user}/status', 'updateStatus')
                    ->name('status');

                Route::delete('/{user}', 'destroy')
                    ->name('destroy');
            });

        Route::resource('permissions', PermissionController::class)
            ->except(['show']);

        Route::resource('roles', RoleController::class)
            ->except(['show']);
    });
