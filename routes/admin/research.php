<?php

use App\Http\Controllers\Admin\Research\ResearchController;
use App\Http\Controllers\Admin\Research\ResearchEvidenceController;
use App\Http\Controllers\Admin\Research\ResearchFindingController;
use App\Http\Controllers\Admin\Research\ResearchFindingQuestionController;
use App\Http\Controllers\Admin\Research\ResearchInvitationController;
use App\Http\Controllers\Admin\Research\ResearchMemberController;
use App\Http\Controllers\Admin\Research\ResearchMethodologyController;
use App\Http\Controllers\Admin\Research\ResearchQuestionController;
use App\Http\Controllers\Admin\Research\ResearchSourceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Research Management
|--------------------------------------------------------------------------
*/

Route::prefix('admin/research')
    ->name('admin.research.')
    ->middleware('auth')
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Research
        |--------------------------------------------------------------------------
        */

        Route::controller(ResearchController::class)->group(function () {

            Route::get('/', 'index')
                ->name('index')
                ->middleware('permission:research.view');

            Route::get('/create', 'create')
                ->name('create')
                ->middleware('permission:research.create');

            Route::post('/', 'store')
                ->name('store')
                ->middleware('permission:research.create');

            Route::get('/{research}', 'show')
                ->name('show')
                ->middleware('permission:research.view');

            Route::get('/{research}/edit', 'edit')
                ->name('edit')
                ->middleware('permission:research.edit');

            Route::put('/{research}', 'update')
                ->name('update')
                ->middleware('permission:research.edit');

            Route::delete('/{research}', 'destroy')
                ->name('destroy')
                ->middleware('permission:research.delete');
        });

        /*
        |--------------------------------------------------------------------------
        | Research Methodology
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/methodology')
            ->name('methodology.')
            ->controller(ResearchMethodologyController::class)
            ->group(function () {

                Route::get('/', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.methodology.view');

                Route::put('/', 'update')
                    ->name('update')
                    ->middleware('permission:research.methodology.edit');
            });

        /*
        |--------------------------------------------------------------------------
        | Research Sources
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/sources')
            ->name('sources.')
            ->controller(ResearchSourceController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.sources.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.sources.create');

                Route::get('/{source}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.sources.edit');

                Route::put('/{source}', 'update')
                    ->name('update')
                    ->middleware('permission:research.sources.edit');

                Route::delete('/{source}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.sources.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Research Questions
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/questions')
            ->name('questions.')
            ->controller(ResearchQuestionController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.questions.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.questions.create');

                Route::get('/{question}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.questions.edit');

                Route::put('/{question}', 'update')
                    ->name('update')
                    ->middleware('permission:research.questions.edit');

                Route::delete('/{question}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.questions.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Research Findings
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/findings')
            ->name('findings.')
            ->controller(ResearchFindingController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.findings.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.findings.create');

                Route::get('/{finding}', 'show')
                    ->name('show')
                    ->middleware('permission:research.findings.view');

                Route::get('/{finding}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.findings.edit');

                Route::put('/{finding}', 'update')
                    ->name('update')
                    ->middleware('permission:research.findings.edit');

                Route::delete('/{finding}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.findings.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Finding Evidence
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/findings/{finding}/evidence')
            ->name('findings.evidence.')
            ->controller(ResearchEvidenceController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.evidence.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.evidence.create');

                Route::get('/{evidence}/edit', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.evidence.edit');

                Route::put('/{evidence}', 'update')
                    ->name('update')
                    ->middleware('permission:research.evidence.edit');

                Route::delete('/{evidence}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.evidence.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Finding Questions
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/findings/{finding}/questions')
            ->name('findings.questions.')
            ->controller(ResearchFindingQuestionController::class)
            ->group(function () {

                Route::get('/', 'edit')
                    ->name('edit')
                    ->middleware('permission:research.findings.questions.view');

                Route::put('/', 'update')
                    ->name('update')
                    ->middleware('permission:research.findings.questions.edit');
            });

        /*
        |--------------------------------------------------------------------------
        | Research Team
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/team')
            ->name('team.')
            ->controller(ResearchMemberController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.team.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.team.create');

                Route::put('/{member}', 'update')
                    ->name('update')
                    ->middleware('permission:research.team.edit');

                Route::delete('/{member}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.team.delete');
            });

        /*
        |--------------------------------------------------------------------------
        | Research Invitations
        |--------------------------------------------------------------------------
        */

        Route::prefix('{research}/invitations')
            ->name('invitations.')
            ->controller(ResearchInvitationController::class)
            ->group(function () {

                Route::get('/', 'index')
                    ->name('index')
                    ->middleware('permission:research.invitations.view');

                Route::post('/', 'store')
                    ->name('store')
                    ->middleware('permission:research.invitations.create');

                Route::delete('/{invitation}', 'destroy')
                    ->name('destroy')
                    ->middleware('permission:research.invitations.delete');
            });
    });