<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StaffLoginController extends Controller
{
    // Show staff login form
    public function show(): Response
    {
        return Inertia::render('Auth/StaffLogin');
    }

    // Handle staff login request
    public function store(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::guard('staff')->attempt($credentials)) {
            $request->session()->regenerate();
            
            $user = Auth::guard('staff')->user();
            
            // Redirect based on role
            return match($user->getRoleNames()->first()) {
                'owner', 'admin' => redirect()->route('staff.dashboard'),
                'manager' => redirect()->route('manager.dashboard'),
                'cook' => redirect()->route('kitchen.dashboard'),
                'delivery' => redirect()->route('delivery.dashboard'),
                'cashier' => redirect()->route('cashier.dashboard'),
                default => redirect()->route('staff.dashboard'),
            };
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle staff logout
    public function destroy(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/staff/login');
    }
}
