<?php

use App\Http\Controllers\Admin\ContentParticipant\ContentParticipantController;
use App\Http\Controllers\Admin\ContentParticipant\ParticipantRoleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'verified'])
    ->prefix('admin/participants')
    ->name('admin.participants.')
    ->group(function () {

        // Participant Roles
        Route::prefix('roles')
            ->name('roles.')
            ->controller(ParticipantRoleController::class)
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:participants.roles.index');

                Route::get('/create', 'create')
                    ->name('create')
                    ->middleware('permission:participant-roles.create');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:participants.roles.create');

                Route::get('/{participantRole}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:participants.roles.update');

                Route::put('/{participantRole}', 'update')
                    ->name('update')
                    ->middleware('permission:participants.roles.update');

                Route::delete('/{participantRole}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:participants.roles.delete');
            });

        // Content Participants
        Route::prefix('{type}/{content}')
            ->controller(ContentParticipantController::class)
            ->group(function () {
                Route::get('/', 'index')
                    ->name('index');

                Route::post('/', 'store')
                    ->name('store');

                Route::put('/{participant}', 'update')
                    ->name('update');

                Route::delete('/{participant}', 'destroy')
                    ->name('destroy');
            });
    });