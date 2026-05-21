<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Staff\LoginController;
use Inertia\Inertia;

Route::prefix('staff')->name('staff.')->group(function () {
    // Staff authentication routes
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'store']);
    Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

    // Staff dashboard (protected)
    Route::get('/dashboard', function () {
        return Inertia::render('Staff/Dashboard');
    })->middleware(['auth:staff'])->name('dashboard');

    // Staff dictionaries
    Route::get('/dictionaries/{dictionary?}', function ($dictionary = 'ingredients') {
        return Inertia::render('Staff/Dictionaries/Index', ['dictionary' => $dictionary]);
    })->middleware(['auth:staff'])->name('dictionaries');

    // Staff storage
    Route::get('/storage', function () {
        return Inertia::render('Staff/Storage/Index');
    })->middleware(['auth:staff'])->name('storage');
});
