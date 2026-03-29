<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\StaffLoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

// Главная страница
Route::get('/', function () {
    return Inertia::render('Welcome');
});

// Customer authentication routes
Route::get('/login', function () {
    return Inertia::render('Auth/Login');
})->name('login');
Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    if (Auth::guard('web')->attempt($credentials)) {
        $request->session()->regenerate();
        
        return redirect()->intended('/dashboard');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
});
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    return redirect('/');
})->name('logout');

Route::get('/register', function () {
    return Inertia::render('Auth/Register');
})->name('register');
Route::post('/register', function (Request $request) {
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:clients',
        'password' => 'required|string|confirmed|min:8',
        'phone' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:500',
    ]);

    try {
        DB::beginTransaction();

        // Create client
        $client = \App\Models\Client::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'],
            'is_active' => true,
        ]);

        // Create default address if provided
        if (!empty($validated['address'])) {
            \App\Models\ClientAddress::create([
                'client_id' => $client->id,
                'address_line_1' => $validated['address'],
                'city' => 'Default City',
                'postal_code' => '00000',
                'country' => 'Italy',
                'is_default' => true,
                'address_type' => 'home',
            ]);
        }

        // Create preferences
        \App\Models\ClientPreference::create([
            'client_id' => $client->id,
            'email_notifications' => true,
            'sms_notifications' => false,
            'promotional_emails' => true,
            'loyalty_points' => 0,
            'discount_level' => 'bronze',
            'total_spent' => 0,
            'total_orders' => 0,
        ]);

        DB::commit();

        event(new \Illuminate\Auth\Events\Registered($client));

        Auth::guard('web')->login($client);

        return redirect()->intended('/dashboard');

    } catch (\Exception $e) {
        DB::rollBack();
        
        // Log the actual error for debugging
        \Log::error('Registration failed: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return back()->withErrors([
            'email' => 'Registration failed: ' . $e->getMessage(),
        ])->withInput();
    }
});

// Staff authentication routes
Route::prefix('staff')->name('staff.')->group(function () {
    Route::get('/login', [StaffLoginController::class, 'show'])->name('login');
    Route::post('/login', [StaffLoginController::class, 'store']);
    Route::post('/logout', [StaffLoginController::class, 'destroy'])->name('logout');
    
    // Staff dashboard (protected)
    Route::get('/dashboard', function () {
        return Inertia::render('Staff/Dashboard');
    })->middleware(['auth:staff'])->name('dashboard');
});

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
