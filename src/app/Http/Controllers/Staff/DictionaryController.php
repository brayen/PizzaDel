<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use App\Http\Requests\StoreIngredientRequest;
use App\Http\Requests\StorePizzaRequest;

class DictionaryController extends Controller
{
    /**
     * Get dictionary configuration
     */
    protected function getDictionaryConfig($dictionary)
    {
        $config = Config::get("dictionaries.{$dictionary}");
        if (!$config) {
            abort(404, 'Dictionary not found');
        }
        return $config;
    }

    /**
     * Get appropriate Form Request based on dictionary
     */
    protected function getFormRequest($dictionary)
    {
        return match($dictionary) {
            'ingredients' => StoreIngredientRequest::class,
            'pizzas' => StorePizzaRequest::class,
            default => abort(404, 'Dictionary not found'),
        };
    }

    /**
     * Display a listing of items from a dictionary.
     */
    public function index(Request $request, $dictionary)
    {
        try {
            $config = $this->getDictionaryConfig($dictionary);
            $model = $config['model'];

            $items = $model::orderBy('name')->get();

            return response()->json([
                'items' => $items,
                'fields' => $config['fields'],
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to get dictionary {$dictionary}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load dictionary data'], 500);
        }
    }

    /**
     * Store a newly created item in a dictionary.
     */
    public function store(Request $request, $dictionary)
    {
        try {
            $config = $this->getDictionaryConfig($dictionary);
            $model = $config['model'];
            $requestClass = $this->getFormRequest($dictionary);

            $validated = app($requestClass)->validated();

            $item = $model::create($validated);

            // Handle translations
            if ($request->has('translations') && method_exists($item, 'saveTranslations')) {
                $item->saveTranslations($request->input('translations'));
            }

            return response()->json([
                'item' => $item->fresh(),
            ], 201);
        } catch (\Exception $e) {
            \Log::error("Failed to store item in dictionary {$dictionary}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to create item'], 500);
        }
    }

    /**
     * Display the specified item.
     */
    public function show(Request $request, $dictionary, $id)
    {
        try {
            $config = $this->getDictionaryConfig($dictionary);
            $model = $config['model'];

            $item = $model::findOrFail($id);

            return response()->json([
                'item' => $item,
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to get item {$id} from dictionary {$dictionary}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to load item'], 500);
        }
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, $dictionary, $id)
    {
        try {
            $config = $this->getDictionaryConfig($dictionary);
            $model = $config['model'];
            $requestClass = $this->getFormRequest($dictionary);

            $item = $model::findOrFail($id);
            $validated = app($requestClass)->validated();

            $item->update($validated);

            // Handle translations
            if ($request->has('translations') && method_exists($item, 'saveTranslations')) {
                $item->saveTranslations($request->input('translations'));
            }

            return response()->json([
                'item' => $item->fresh(),
            ]);
        } catch (\Exception $e) {
            \Log::error("Failed to update item {$id} in dictionary {$dictionary}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to update item'], 500);
        }
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Request $request, $dictionary, $id)
    {
        try {
            $config = $this->getDictionaryConfig($dictionary);
            $model = $config['model'];

            $item = $model::findOrFail($id);
            $item->delete();

            return response()->json(null, 204);
        } catch (\Exception $e) {
            \Log::error("Failed to delete item {$id} from dictionary {$dictionary}: " . $e->getMessage());
            return response()->json(['error' => 'Failed to delete item'], 500);
        }
    }

    /**
     * Get available dictionaries
     */
    public function dictionaries()
    {
        try {
            return response()->json([
                'dictionaries' => array_keys(Config::get('dictionaries', [])),
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to get dictionaries: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load dictionaries'], 500);
        }
    }
}
