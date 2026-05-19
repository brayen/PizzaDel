<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class IngredientController extends Controller
{
    /**
     * Display a listing of ingredients.
     */
    public function index()
    {
        $ingredients = Ingredient::orderBy('name')->get();
        
        return response()->json([
            'ingredients' => $ingredients,
        ]);
    }

    /**
     * Store a newly created ingredient.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ingredients,slug',
            'description' => 'nullable|string',
            'price_per_gram' => 'nullable|numeric|min:0',
            'calories_per_gram' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $ingredient = Ingredient::create($validated);

        return response()->json([
            'ingredient' => $ingredient,
        ], 201);
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Ingredient $ingredient)
    {
        return response()->json([
            'ingredient' => $ingredient,
        ]);
    }

    /**
     * Update the specified ingredient.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:ingredients,slug,' . $ingredient->id,
            'description' => 'nullable|string',
            'price_per_gram' => 'nullable|numeric|min:0',
            'calories_per_gram' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
        ]);

        if (empty($validated['slug'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $ingredient->update($validated);

        return response()->json([
            'ingredient' => $ingredient->fresh(),
        ]);
    }

    /**
     * Remove the specified ingredient.
     */
    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();

        return response()->json(null, 204);
    }
}
