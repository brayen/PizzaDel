<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Pizza;
use App\Models\Ingredient;
use Illuminate\Http\Request;

class PizzaController extends Controller
{
    public function storeIngredients(Request $request, $pizzaId)
    {
        $pizza = Pizza::findOrFail($pizzaId);
        $ingredients = $request->input('ingredients', []);

        // Sync ingredients with quantities
        foreach ($ingredients as $ingredientData) {
            $pizza->ingredients()->syncWithoutDetaching([
                $ingredientData['id'] => [
                    'quantity_in_grams' => $ingredientData['quantity_in_grams'],
                ],
            ]);
        }

        return response()->json([
            'pizza' => $pizza->fresh(),
        ]);
    }

    public function updateIngredient(Request $request, $pizzaId, $ingredientId)
    {
        $pizza = Pizza::findOrFail($pizzaId);
        $quantity = $request->input('quantity_in_grams');

        $pizza->ingredients()->updateExistingPivot($ingredientId, [
            'quantity_in_grams' => $quantity,
        ]);

        return response()->json([
            'pizza' => $pizza->fresh(),
        ]);
    }

    public function removeIngredient(Request $request, $pizzaId, $ingredientId)
    {
        $pizza = Pizza::findOrFail($pizzaId);
        $pizza->ingredients()->detach($ingredientId);

        return response()->json([
            'pizza' => $pizza->fresh(),
        ]);
    }

    public function getIngredients($pizzaId)
    {
        $pizza = Pizza::findOrFail($pizzaId);
        $ingredients = $pizza->ingredients()->withPivot('quantity_in_grams')->get();

        return response()->json([
            'ingredients' => $ingredients,
        ]);
    }
}
