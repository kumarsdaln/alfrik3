<?php

use App\Http\Controllers\Public\Research\ResearchController;
use App\Http\Controllers\Public\Research\ResearchInvitationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Research
|--------------------------------------------------------------------------
*/

Route::name('research.')
    ->prefix('research')
    ->group(function () {
        Route::get('/', [ResearchController::class, 'index'])
            ->name('index');

        Route::get('/{research:slug}', [ResearchController::class, 'show'])
            ->name('show');

        Route::get('/{research:slug}/download', [ResearchController::class, 'download'])
            ->name('download');
    });

/*
|--------------------------------------------------------------------------
| Research Invitations
|--------------------------------------------------------------------------
*/

Route::prefix('research/invitations')
    ->name('research.invitation.')
    ->group(function () {
        Route::get('/{token}', [ResearchInvitationController::class, 'show'])
            ->name('show');

        Route::post('/{token}/accept', [ResearchInvitationController::class, 'accept'])
            ->middleware('auth')
            ->name('accept');

        Route::get('/{token}/accepted', [ResearchInvitationController::class, 'accepted'])
            ->name('accepted');
    });