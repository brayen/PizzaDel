<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TranslationService
{
    /**
     * Get translated field from model with fallback
     */
    public static function getTranslated($model, string $field, string $locale = 'en'): string
    {
        $translations = $model->translations ?? [];
        
        return $translations[$locale][$field] ?? 
               $translations['en'][$field] ?? 
               $field;
    }

    /**
     * Get multiple translated fields
     */
    public static function getMultipleTranslated($model, array $fields, string $locale = 'en'): array
    {
        $result = [];
        $translations = $model->translations ?? [];

        foreach ($fields as $field) {
            $result[$field] = $translations[$locale][$field] ?? 
                            $translations['en'][$field] ?? 
                            $field;
        }

        return $result;
    }

    /**
     * Update translations for a model
     */
    public static function updateTranslations($model, array $translations, string $locale): void
    {
        $currentTranslations = $model->translations ?? [];
        $currentTranslations[$locale] = array_merge($currentTranslations[$locale] ?? [], $translations);
        
        $model->translations = $currentTranslations;
        $model->save();
    }

    /**
     * Get products with cached translations
     */
    public static function getCachedProducts(string $locale = 'en', int $limit = 20): array
    {
        return Cache::remember("products.translated.{$locale}.{$limit}", 3600, function() use ($locale, $limit) {
            return DB::table('products')
                ->where('is_active', true)
                ->orderBy('sort_order')
                ->limit($limit)
                ->get()
                ->map(function($product) use ($locale) {
                    $translations = json_decode($product->translations, true) ?? [];
                    
                    return [
                        'id' => $product->id,
                        'sku' => $product->sku,
                        'slug' => $product->slug,
                        'name' => $translations[$locale]['name'] ?? $translations['en']['name'] ?? 'Product',
                        'description' => $translations[$locale]['description'] ?? $translations['en']['description'] ?? '',
                        'price' => '€' . number_format($product->base_price / 100, 2),
                        'meta_title' => $translations[$locale]['meta_title'] ?? $translations['en']['meta_title'] ?? '',
                        'meta_description' => $translations[$locale]['meta_description'] ?? $translations['en']['meta_description'] ?? '',
                    ];
                })
                ->toArray();
        });
    }

    /**
     * Clear translation cache
     */
    public static function clearCache(string $model = 'products'): void
    {
        $locales = array_keys(config('languages.supported', ['en']));
        $limits = [20, 50, 100];

        foreach ($locales as $locale) {
            foreach ($limits as $limit) {
                Cache::forget("{$model}.translated.{$locale}.{$limit}");
                Cache::forget("{$model}.{$locale}.{$limit}");
            }
        }
    }

    /**
     * Validate translation structure
     */
    public static function validateTranslationStructure(array $translations, array $requiredFields = ['name']): bool
    {
        $locales = array_keys(config('languages.supported', ['en']));
        
        foreach ($locales as $locale) {
            if (!isset($translations[$locale])) {
                return false;
            }

            foreach ($requiredFields as $field) {
                if (!isset($translations[$locale][$field]) || empty($translations[$locale][$field])) {
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Get missing translations for a model
     */
    public static function getMissingTranslations($model): array
    {
        $missing = [];
        $translations = $model->translations ?? [];
        $locales = array_keys(config('languages.supported', ['en']));
        $requiredFields = ['name', 'description'];

        foreach ($locales as $locale) {
            foreach ($requiredFields as $field) {
                if (empty($translations[$locale][$field])) {
                    $missing[] = "{$locale}.{$field}";
                }
            }
        }

        return $missing;
    }
}
