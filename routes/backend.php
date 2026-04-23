<?php

use App\Http\Controllers\Web\Backend\CreditsController;
use App\Http\Controllers\Web\Backend\DashboardController;
use App\Http\Controllers\Web\Backend\Faq\FaqController;
use App\Http\Controllers\Web\Backend\TutorialController;
use App\Http\Controllers\Web\Backend\User\UsersController;
use Illuminate\Support\Facades\Route;



Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');

 Route::prefix('users')->name('admin.users.')->group(function () {
    Route::resource('/', UsersController::class)->parameter('', 'user');
    Route::patch('/{user}/status', [UsersController::class, 'changeStatus'])->name('status');
});

// FAQs Management
Route::prefix('faqs')->name('admin.faqs.')->group(function () {
    Route::resource('/', FaqController::class)->parameter('', 'faq');
    Route::patch('/status/{faq}', [FaqController::class, 'status'])->name('status');
});

// Tutorials Management
Route::prefix('tutorials')->name('admin.tutorials.')->group(function () {
    Route::resource('/', TutorialController::class)->parameter('', 'tutorial');
    Route::patch('/status/{tutorial}', [TutorialController::class, 'status'])->name('status');
});

// Credits Management
Route::prefix('credits')->name('admin.credits.')->group(function () {
    Route::resource('/', CreditsController::class)->parameter('', 'credit');
    Route::patch('/most-popular/{credit}', [CreditsController::class, 'mostPopular'])->name('most-popular');
});