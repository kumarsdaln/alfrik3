<?php

use App\Http\Controllers\Admin\User\PermissionController;
use App\Http\Controllers\Admin\User\RoleController;
use App\Http\Controllers\Admin\User\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin User Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin')
    ->name('admin.')
    ->middleware('auth')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Users
        |--------------------------------------------------------------------------
        */

        Route::prefix('users')
            ->name('users.')
            ->scopeBindings()
            ->controller(UserController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:users.view');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:users.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:users.create');

                Route::get('/{user}', 'show')
                    ->name('show')
                    ->middleware('permission:users.view');

                Route::get('/{user}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:users.edit');

                Route::put('/{user}', 'update')
                    ->name('update')
                    ->middleware('permission:users.edit');

                Route::patch('/{user}/status', 'updateStatus')
                    ->name('status')
                    ->middleware('permission:users.edit');

                Route::delete('/{user}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:users.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Roles
        |--------------------------------------------------------------------------
        */

        Route::prefix('roles')
            ->name('roles.')
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

        /*
        |--------------------------------------------------------------------------
        | Permissions
        |--------------------------------------------------------------------------
        */

        Route::prefix('permissions')
            ->name('permissions.')
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
    });