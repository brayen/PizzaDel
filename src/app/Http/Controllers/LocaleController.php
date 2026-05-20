<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;

class LocaleController extends Controller
{
    /**
     * Get translations for a specific locale and context
     */
    public function translations($locale = null, $context = 'public')
    {
        // Use the locale from URL parameter, not from app()->getLocale()
        $locale = $locale ?: config('languages.default', 'en');

        $supportedKeys = array_keys(config('languages.supported', ['en' => 'English']));
        if (!in_array($locale, $supportedKeys)) {
            $locale = config('languages.default', 'en');
        }

        // Validate context (public or staff)
        $context = in_array($context, ['public', 'staff']) ? $context : 'public';

        // Load all translation files for the locale and context
        $translations = [
            'common' => $this->loadTranslations($locale, 'common', $context),
            'auth' => $this->loadTranslations($locale, 'auth', $context),
            'navigation' => $this->loadTranslations($locale, 'navigation', $context),
            'dashboard' => $this->loadTranslations($locale, 'dashboard', $context),
            'footer' => $this->loadTranslations($locale, 'footer', $context),
            'validation' => $this->loadTranslations($locale, 'validation', $context),
            'dictionaries' => $this->loadTranslations($locale, 'dictionaries', $context),
            'sidebar' => $this->loadTranslations($locale, 'sidebar', $context),
            'menu' => $this->loadTranslations($locale, 'menu', $context),
            'cart' => $this->loadTranslations($locale, 'cart', $context),
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
    private function loadTranslations($locale, $file, $context = 'public')
    {
        $path = lang_path("{$context}/{$locale}/{$file}.php");

        if (!file_exists($path)) {
            // Fallback to old structure for backward compatibility
            $oldPath = lang_path("{$locale}/{$file}.php");
            if (file_exists($oldPath)) {
                return require $oldPath;
            }
            return [];
        }

        return require $path;
    }
}
