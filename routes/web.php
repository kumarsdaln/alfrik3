<?php

use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;

Route::get('', WelcomeController::class)->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';


require __DIR__.'/public/event.php';
require __DIR__.'/public/interview.php';
require __DIR__.'/public/report.php';
require __DIR__.'/public/research.php';
require __DIR__.'/public/survey.php';
require __DIR__.'/public/magazine.php';
require __DIR__.'/public/expert.php';
require __DIR__.'/public/profile.php';


//Admin Routes
require __DIR__.'/admin/user.php';
require __DIR__.'/admin/event.php';
require __DIR__.'/admin/interview.php';
require __DIR__.'/admin/report.php';
require __DIR__.'/admin/research.php';
require __DIR__.'/admin/survey.php';
require __DIR__.'/admin/magazine.php';
