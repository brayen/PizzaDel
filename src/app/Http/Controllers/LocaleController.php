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

        // Load all translation files for the locale
        $translations = [
            'common' => $this->loadTranslations($locale, 'common'),
            'auth' => $this->loadTranslations($locale, 'auth'),
            'navigation' => $this->loadTranslations($locale, 'navigation'),
            'dashboard' => $this->loadTranslations($locale, 'dashboard'),
            'footer' => $this->loadTranslations($locale, 'footer'),
            'validation' => $this->loadTranslations($locale, 'validation'),
        ];

        return response()->json($translations);
    }

    /**
     * Switch locale
     */
    public function switch(Request $request)
    {
        $locale = $request->input('locale');
        
        $supported = config('languages.supported', ['en']);
        $supportedKeys = array_keys($supported);

        if (in_array($locale, $supportedKeys)) {
            Session::put('locale', $locale);
            app()->setLocale($locale);
        }

        return back();
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
