<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\StockMovement;
use App\Models\Ingredient;
use App\Http\Requests\CreateInboundRequest;
use App\Http\Requests\CreateOutboundRequest;
use Illuminate\Http\Request;

class StorageController extends Controller
{
    public function index()
    {
        try {
            $ingredients = Ingredient::with(['stockMovements' => function ($query) {
                $query->orderBy('date', 'desc')->limit(10);
            }])->get()->map(function ($ingredient) {
                $ingredient->current_stock = StockMovement::calculateStock($ingredient->id);
                return $ingredient;
            });

            return response()->json([
                'ingredients' => $ingredients,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get storage index: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load storage data'], 500);
        }
    }

    public function getStock($ingredientId)
    {
        try {
            $stock = StockMovement::calculateStock($ingredientId);
            $movements = StockMovement::with('ingredient')
                ->forIngredient($ingredientId)
                ->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'stock' => $stock,
                'movements' => $movements,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get stock for ingredient ' . $ingredientId . ': ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load stock data'], 500);
        }
    }

    public function createInbound(CreateInboundRequest $request)
    {
        try {
            $validated = $request->validated();

            $movement = StockMovement::create([
                'ingredient_id' => $validated['ingredient_id'],
                'type' => 'inbound',
                'quantity' => $validated['quantity'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
                'reference' => $validated['reference'] ?? null,
            ]);

            return response()->json([
                'movement' => $movement,
                'stock' => StockMovement::calculateStock($validated['ingredient_id']),
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create inbound movement: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create inbound movement'], 500);
        }
    }

    public function createOutbound(CreateOutboundRequest $request)
    {
        try {
            $validated = $request->validated();

            $currentStock = StockMovement::calculateStock($validated['ingredient_id']);
            if ($validated['quantity'] > $currentStock) {
                return response()->json([
                    'error' => 'Insufficient stock',
                    'current_stock' => $currentStock,
                ], 400);
            }

            $movement = StockMovement::create([
                'ingredient_id' => $validated['ingredient_id'],
                'type' => 'outbound',
                'quantity' => $validated['quantity'],
                'date' => $validated['date'],
                'notes' => $validated['notes'] ?? null,
                'reference' => $validated['reference'] ?? null,
            ]);

            return response()->json([
                'movement' => $movement,
                'stock' => StockMovement::calculateStock($validated['ingredient_id']),
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Failed to create outbound movement: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create outbound movement'], 500);
        }
    }

    public function getMovements(Request $request)
    {
        try {
            $type = $request->query('type');
            $ingredientId = $request->query('ingredient_id');

            $query = StockMovement::with('ingredient');

            if ($type) {
                $query->ofType($type);
            }

            if ($ingredientId) {
                $query->forIngredient($ingredientId);
            }

            $movements = $query->orderBy('date', 'desc')
                ->orderBy('created_at', 'desc')
                ->paginate(20);

            return response()->json($movements);
        } catch (\Exception $e) {
            \Log::error('Failed to get movements: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load movements'], 500);
        }
    }
}
