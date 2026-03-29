<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use Inertia\Inertia;

// Главная страница
Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Authentication routes
Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->name('logout');

Route::get('/register', [RegisterController::class, 'show'])->name('register');
Route::post('/register', [RegisterController::class, 'store']);

// Меню пиццы
Route::get('/menu', function () {
    return Inertia::render('Menu');
})->name('menu');

// Корзина
Route::get('/cart', function () {
    return Inertia::render('Cart');
})->name('cart');

// Оформление заказа
Route::get('/checkout', function () {
    return Inertia::render('Checkout');
})->name('checkout');

// Панель управления (для клиентов)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth'])->name('dashboard');

// Админ панель
Route::get('/admin', function () {
    return Inertia::render('Admin/Dashboard');
})->middleware(['auth', 'admin'])->name('admin.dashboard');
