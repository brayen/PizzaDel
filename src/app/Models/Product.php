<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'slug',
        'base_price',
        'translations',
        'is_active',
        'sort_order',
        'category_id',
    ];

    protected $casts = [
        'base_price' => 'integer',
        'translations' => 'array',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get translated name for a locale
     */
    public function getName(string $locale = 'en'): string
    {
        return $this->translations[$locale]['name'] ?? 
               $this->translations['en']['name'] ?? 
               'Product';
    }

    /**
     * Get translated description for a locale
     */
    public function getDescription(string $locale = 'en'): string
    {
        return $this->translations[$locale]['description'] ?? 
               $this->translations['en']['description'] ?? 
               '';
    }

    /**
     * Get translated meta title for a locale
     */
    public function getMetaTitle(string $locale = 'en'): string
    {
        return $this->translations[$locale]['meta_title'] ?? 
               $this->translations['en']['meta_title'] ?? 
               $this->getName($locale);
    }

    /**
     * Get translated meta description for a locale
     */
    public function getMetaDescription(string $locale = 'en'): string
    {
        return $this->translations[$locale]['meta_description'] ?? 
               $this->translations['en']['meta_description'] ?? 
               $this->getDescription($locale);
    }

    /**
     * Get price in euros
     */
    public function getPriceEurosAttribute(): float
    {
        return $this->base_price / 100;
    }

    /**
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '€' . number_format($this->price_euros, 2);
    }

    /**
     * Scope for active products
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for sorting
     */
    public function scopeSorted($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get products with translations cached
     */
    public static function getCached(string $locale = 'en', int $limit = 20)
    {
        return Cache::remember("products.{$locale}.{$limit}", 3600, function() use ($locale, $limit) {
            return self::active()
                ->with('category')
                ->limit($limit)
                ->get()
                ->map(function($product) use ($locale) {
                    return [
                        'id' => $product->id,
                        'sku' => $product->sku,
                        'slug' => $product->slug,
                        'name' => $product->getName($locale),
                        'description' => $product->getDescription($locale),
                        'price' => $product->formatted_price,
                        'category' => $product->category?->getName($locale),
                    ];
                });
        });
    }

    /**
     * Clear product cache
     */
    public static function clearCache()
    {
        $locales = config('languages.supported', ['en']);
        foreach ($locales as $locale => $name) {
            Cache::forget("products.{$locale}.20");
            Cache::forget("products.{$locale}.50");
            Cache::forget("products.{$locale}.100");
        }
    }

    /**
     * Boot model events
     */
    protected static function booted()
    {
        static::updated(function () {
            self::clearCache();
        });

        static::deleted(function () {
            self::clearCache();
        });
    }

    /**
     * Relationship with category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
