<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function store(RegisterRequest $request)
    {
        $validated = $request->validated();

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

            \Log::error('Registration failed: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return back()->withErrors([
                'email' => 'Registration failed: ' . $e->getMessage(),
            ])->withInput();
        }
    }
}
