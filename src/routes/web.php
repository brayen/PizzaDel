<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Public\LocaleController;
use App\Http\Controllers\Public\ProductController;
use App\Http\Controllers\Staff\DictionaryController;
use App\Http\Controllers\Staff\PizzaController;
use App\Http\Controllers\Staff\StorageController;
use Inertia\Inertia;

// Include route files
require __DIR__.'/customer.php';
require __DIR__.'/staff.php';

// Language routes
Route::get('/translations/{locale}/{context?}', [LocaleController::class, 'translations']);
Route::post('/locale', [LocaleController::class, 'switch']);

// Products routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/product/{slug}', [ProductController::class, 'show'])->name('products.show');
Route::get('/api/products', [ProductController::class, 'api'])->name('products.api');
Route::get('/api/search', [ProductController::class, 'search'])->name('products.search');

// Dictionary API routes
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/dictionaries', [DictionaryController::class, 'dictionaries'])->name('dictionaries');
    Route::get('/dictionaries/{dictionary}', [DictionaryController::class, 'index'])->name('dictionaries.index');
    Route::post('/dictionaries/{dictionary}', [DictionaryController::class, 'store'])->name('dictionaries.store');
    Route::get('/dictionaries/{dictionary}/{id}', [DictionaryController::class, 'show'])->name('dictionaries.show');
    Route::put('/dictionaries/{dictionary}/{id}', [DictionaryController::class, 'update'])->name('dictionaries.update');
    Route::delete('/dictionaries/{dictionary}/{id}', [DictionaryController::class, 'destroy'])->name('dictionaries.destroy');

    // Pizza ingredient management routes
    Route::post('/pizzas/{pizzaId}/ingredients', [PizzaController::class, 'storeIngredients'])->name('pizzas.ingredients.store');
    Route::put('/pizzas/{pizzaId}/ingredients/{ingredientId}', [PizzaController::class, 'updateIngredient'])->name('pizzas.ingredients.update');
    Route::delete('/pizzas/{pizzaId}/ingredients/{ingredientId}', [PizzaController::class, 'removeIngredient'])->name('pizzas.ingredients.remove');
    Route::get('/pizzas/{pizzaId}/ingredients', [PizzaController::class, 'getIngredients'])->name('pizzas.ingredients.index');

    // Storage management routes
    Route::get('/storage', [StorageController::class, 'index'])->name('storage.index');
    Route::get('/storage/stock/{ingredientId}', [StorageController::class, 'getStock'])->name('storage.stock');
    Route::post('/storage/inbound', [StorageController::class, 'createInbound'])->name('storage.inbound');
    Route::post('/storage/outbound', [StorageController::class, 'createOutbound'])->name('storage.outbound');
    Route::get('/storage/movements', [StorageController::class, 'getMovements'])->name('storage.movements');
});

// Language switch routes (fallback)
Route::get('/locale/{locale}', [LocaleController::class, 'switchGet'])->name('locale.switch');

// Main Page
Route::get('/', function () {
    return Inertia::render('Public/Welcome');
});

// Pizza menu
Route::get('/menu', function () {
    return Inertia::render('Public/Menu');
})->name('menu');

// Basket
Route::get('/cart', function () {
    return Inertia::render('Public/Cart');
})->name('cart');

// Order
Route::get('/checkout', function () {
    return Inertia::render('Public/Checkout');
})->name('checkout');

