<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Session;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        return [
            ...parent::share($request),
            'auth' => [
                'user' => function () use ($request) {
                    $user = $request->user();
                    
                    if ($user) {
                        // Load relationships for clients
                        if ($user instanceof \App\Models\Client) {
                            $user->load(['preferences', 'addresses']);
                        }
                        
                        // Load roles for staff
                        if ($user instanceof \App\Models\Staff) {
                            $user->load('roles');
                        }
                    }
                    
                    return $user;
                },
            ],
            'locale' => function () use ($request) {
                $locale = Session::get('locale', config('languages.default', 'en'));
                
                // Debug log
                \Log::info('HandleInertiaRequests - locale from session: ' . $locale);
                
                return $locale;
            },
            'translations' => function () use ($request) {
                $locale = Session::get('locale', config('languages.default', 'en'));
                
                // Load basic translations for initial render
                $translations = [
                    'common' => $this->loadTranslations($locale, 'common'),
                    'navigation' => $this->loadTranslations($locale, 'navigation'),
                ];
                
                // Debug log
                \Log::info('HandleInertiaRequests - translations for ' . $locale . ': ' . json_encode(array_keys($translations)));
                
                return $translations;
            },
        ];
    }

    /**
     * Load translations from language files
     */
    private function loadTranslations($locale, $file)
    {
        $path = lang_path("{$locale}/{$file}.php");
        
        if (!file_exists($path)) {
            return [];
        }

        return require $path;
    }
}
