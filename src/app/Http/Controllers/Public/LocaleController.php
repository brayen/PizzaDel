<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
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

        // Auto-load all translation files for the locale and context
        $translations = $this->loadAllTranslations($locale, $context);

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
     * Switch locale via GET request
     */
    public function switchGet($locale)
    {
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

    /**
     * Load all translation files for a locale and context
     */
    private function loadAllTranslations($locale, $context = 'public')
    {
        $translations = [];
        $path = lang_path("{$context}/{$locale}");

        if (!is_dir($path)) {
            // Fallback to old structure
            $oldPath = lang_path($locale);
            if (is_dir($oldPath)) {
                $files = glob("{$oldPath}/*.php");
                foreach ($files as $file) {
                    $filename = basename($file, '.php');
                    $translations[$filename] = require $file;
                }
            }
            return $translations;
        }

        // Load all PHP files from the context directory
        $files = glob("{$path}/*.php");
        foreach ($files as $file) {
            $filename = basename($file, '.php');
            $translations[$filename] = require $file;
        }

        return $translations;
    }
}
