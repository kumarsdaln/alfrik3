<?php

use App\Http\Controllers\Public\Magazine\MagazineArticleController;
use App\Http\Controllers\Public\Magazine\MagazineController;
use App\Http\Controllers\Public\Magazine\MagazineIssueController;
use Illuminate\Support\Facades\Route;

Route::prefix('magazine')
    ->name('magazine.')
    ->group(function () {

        /*
        |--------------------------------------------------------------------------
        | Magazine
        |--------------------------------------------------------------------------
        */

        Route::get('/', [MagazineController::class, 'index'])
            ->name('index');

        Route::get('/{magazine:slug}', [MagazineController::class, 'show'])
            ->name('show');

        /*
        |--------------------------------------------------------------------------
        | Issues
        |--------------------------------------------------------------------------
        */

        Route::get('/{magazine:slug}/issues/{issue:slug}', [
            MagazineIssueController::class,
            'show',
        ])->name('issues.show');

        /*
        |--------------------------------------------------------------------------
        | Articles
        |--------------------------------------------------------------------------
        */

        Route::get('/articles/{article:slug}', [
            MagazineArticleController::class,
            'show',
        ])->name('articles.show');
    });
