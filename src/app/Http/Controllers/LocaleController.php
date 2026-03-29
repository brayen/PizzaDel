<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class LocaleController extends Controller
{
    /**
     * Get translations for a specific locale
     */
    public function translations($locale = null)
    {
        // Use the locale from URL parameter, not from app()->getLocale()
        $locale = $locale ?: config('languages.default', 'en');
        
        $supportedKeys = array_keys(config('languages.supported', ['en' => 'English']));
        if (!in_array($locale, $supportedKeys)) {
            $locale = config('languages.default', 'en');
        }

        // Debug log
        \Log::info('LocaleController - loading translations for: ' . $locale . ' (from URL parameter)');

        // Load all translation files for the locale
        $translations = [
            'common' => $this->loadTranslations($locale, 'common'),
            'auth' => $this->loadTranslations($locale, 'auth'),
            'navigation' => $this->loadTranslations($locale, 'navigation'),
            'dashboard' => $this->loadTranslations($locale, 'dashboard'),
            'footer' => $this->loadTranslations($locale, 'footer'),
            'validation' => $this->loadTranslations($locale, 'validation'),
        ];

        // Debug log
        \Log::info('LocaleController - translations loaded: ' . json_encode(array_keys($translations)));

        return response()->json($translations);
    }

    /**
     * Switch locale
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        
        // Debug log
        \Log::info('LocaleController - switch method called');
        \Log::info('LocaleController - request locale: ' . $locale);
        \Log::info('LocaleController - request method: ' . $request->method());
        \Log::info('LocaleController - all request data: ' . json_encode($request->all()));
        
        // Debug config
        $supported = config('languages.supported', ['en']);
        $supportedKeys = array_keys($supported);
        \Log::info('LocaleController - languages.supported config: ' . json_encode($supported));
        \Log::info('LocaleController - languages.default config: ' . config('languages.default'));
        \Log::info('LocaleController - locale to check: ' . $locale);
        \Log::info('LocaleController - supported keys: ' . json_encode($supportedKeys));
        \Log::info('LocaleController - in_array result: ' . (in_array($locale, $supportedKeys) ? 'true' : 'false'));

        if (in_array($locale, $supportedKeys)) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
            
            // Debug log
            \Log::info('LocaleController - switched to: ' . $locale);
            \Log::info('LocaleController - session locale: ' . Session::get('locale'));
        } else {
            \Log::info('LocaleController - locale not supported: ' . $locale);
        }

        return back();
    }

    /**
     * Load translations from language files
     */
    private function loadTranslations($locale, $file)
    {
        $path = lang_path("{$locale}/{$file}.php");
        
        // Debug log
        \Log::info('LocaleController - loading file: ' . $path);
        \Log::info('LocaleController - file exists: ' . (file_exists($path) ? 'yes' : 'no'));

        if (!file_exists($path)) {
            \Log::info('LocaleController - file not found, returning empty array');
            return [];
        }

        $translations = require $path;
        \Log::info('LocaleController - loaded ' . count($translations) . ' translations from ' . $file);
        
        return $translations;
    }
}
