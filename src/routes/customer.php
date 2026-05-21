<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LoginController;
use App\Http\Controllers\Public\RegisterController;
use App\Http\Controllers\Public\LocaleController;
use Inertia\Inertia;

// Customer authentication routes
Route::get('/login', function () {
    return Inertia::render('Public/Auth/Login');
})->name('login');

Route::post('/login', [LoginController::class, 'store']);

Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register', function () {
    return Inertia::render('Public/Auth/Register');
})->name('register');

Route::post('/register', [RegisterController::class, 'store']);

// Client dashboard
Route::get('/dashboard', function () {
    return Inertia::render('Public/Dashboard');
})->middleware(['auth'])->name('dashboard');
