<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\TranslationService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $locale = app()->getLocale();
        $limit = $request->get('limit', 20);

        $products = TranslationService::getCachedProducts($locale, $limit);

        return Inertia::render('Products/Index', [
            'products' => $products,
            'locale' => $locale,
        ]);
    }

    /**
     * Display the specified product.
     */
    public function show(string $slug)
    {
        $locale = app()->getLocale();
        
        $product = Product::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return Inertia::render('Products/Show', [
            'product' => [
                'id' => $product->id,
                'sku' => $product->sku,
                'slug' => $product->slug,
                'name' => $product->getName($locale),
                'description' => $product->getDescription($locale),
                'price' => $product->formatted_price,
                'meta_title' => $product->getMetaTitle($locale),
                'meta_description' => $product->getMetaDescription($locale),
            ],
            'locale' => $locale,
        ]);
    }

    /**
     * API endpoint for products (for dynamic loading)
     */
    public function api(Request $request)
    {
        $locale = $request->get('locale', session('locale', 'en'));
        $limit = min($request->get('limit', 20), 100);
        $offset = $request->get('offset', 0);

        $products = Product::active()
            ->with('category')
            ->orderBy('sort_order')
            ->offset($offset)
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

        return response()->json([
            'products' => $products,
            'has_more' => $products->count() === $limit,
            'total' => Product::active()->count(),
        ]);
    }

    /**
     * Search products
     */
    public function search(Request $request)
    {
        $locale = app()->getLocale();
        $query = $request->get('q', '');

        if (empty($query)) {
            return response()->json(['products' => []]);
        }

        $products = Product::active()
            ->where(function($q) use ($query, $locale) {
                $q->where("translations->{$locale}->name", 'LIKE', "%{$query}%")
                  ->orWhere("translations->en->name", 'LIKE', "%{$query}%")
                  ->orWhere("translations->{$locale}->description", 'LIKE', "%{$query}%")
                  ->orWhere("translations->en->description", 'LIKE', "%{$query}%");
            })
            ->limit(10)
            ->get()
            ->map(function($product) use ($locale) {
                return [
                    'id' => $product->id,
                    'sku' => $product->sku,
                    'slug' => $product->slug,
                    'name' => $product->getName($locale),
                    'description' => $product->getDescription($locale),
                    'price' => $product->formatted_price,
                ];
            });

        return response()->json(['products' => $products]);
    }
}
