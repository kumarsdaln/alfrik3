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
    ->middleware(['auth', 'role:admin'])
    ->scopeBindings()
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Research
        |--------------------------------------------------------------------------
        */

        Route::controller(ResearchController::class)->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');

            Route::get('/{research}', 'show')->name('show');
            Route::get('/{research}/edit', 'edit')->name('edit');
            Route::put('/{research}', 'update')->name('update');
            Route::delete('/{research}', 'destroy')->name('destroy');
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
                Route::get('/', 'edit')->name('edit');
                Route::put('/', 'update')->name('update');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::get('/{source}/edit', 'edit')->name('edit');
                Route::put('/{source}', 'update')->name('update');
                Route::delete('/{source}', 'destroy')->name('destroy');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::get('/{question}/edit', 'edit')->name('edit');
                Route::put('/{question}', 'update')->name('update');
                Route::delete('/{question}', 'destroy')->name('destroy');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::get('/{finding}', 'show')->name('show');
                Route::get('/{finding}/edit', 'edit')->name('edit');
                Route::put('/{finding}', 'update')->name('update');
                Route::delete('/{finding}', 'destroy')->name('destroy');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::get('/{evidence}/edit', 'edit')->name('edit');
                Route::put('/{evidence}', 'update')->name('update');
                Route::delete('/{evidence}', 'destroy')->name('destroy');
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
                Route::get('/', 'edit')->name('edit');
                Route::put('/', 'update')->name('update');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::put('/{member}', 'update')->name('update');
                Route::delete('/{member}', 'destroy')->name('destroy');
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
                Route::get('/', 'index')->name('index');
                Route::post('/', 'store')->name('store');

                Route::delete('/{invitation}', 'destroy')->name('destroy');
            });
    });