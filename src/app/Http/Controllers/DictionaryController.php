<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class DictionaryController extends Controller
{
    protected $dictionaries = [
        'ingredients' => [
            'model' => \App\Models\Ingredient::class,
            'fields' => [
                'name' => ['type' => 'text', 'required' => true, 'label' => 'Название'],
                'slug' => ['type' => 'text', 'required' => false, 'label' => 'Slug'],
                'description' => ['type' => 'textarea', 'required' => false, 'label' => 'Описание'],
                'price_per_gram' => ['type' => 'number', 'required' => false, 'label' => 'Цена за грамм ($)'],
                'calories_per_gram' => ['type' => 'number', 'required' => false, 'label' => 'Калории за грамм'],
                'is_active' => ['type' => 'boolean', 'required' => false, 'label' => 'Активен'],
            ],
        ],
    ];

    /**
     * Get dictionary configuration
     */
    protected function getDictionaryConfig($dictionary)
    {
        if (!isset($this->dictionaries[$dictionary])) {
            abort(404, 'Dictionary not found');
        }
        return $this->dictionaries[$dictionary];
    }

    /**
     * Display a listing of items from a dictionary.
     */
    public function index(Request $request, $dictionary)
    {
        $config = $this->getDictionaryConfig($dictionary);
        $model = $config['model'];

        $items = $model::orderBy('name')->get();

        return response()->json([
            'items' => $items,
            'fields' => $config['fields'],
        ]);
    }

    /**
     * Store a newly created item in a dictionary.
     */
    public function store(Request $request, $dictionary)
    {
        $config = $this->getDictionaryConfig($dictionary);
        $model = $config['model'];

        $rules = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['required']) {
                $rules[$field] = 'required';
            }
        }

        $validated = $request->validate($rules);

        // Auto-generate slug if not provided and field exists
        if (isset($config['fields']['slug']) && empty($validated['slug'] ?? null) && isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $item = $model::create($validated);

        return response()->json([
            'item' => $item,
        ], 201);
    }

    /**
     * Display the specified item.
     */
    public function show(Request $request, $dictionary, $id)
    {
        $config = $this->getDictionaryConfig($dictionary);
        $model = $config['model'];

        $item = $model::findOrFail($id);

        return response()->json([
            'item' => $item,
        ]);
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, $dictionary, $id)
    {
        $config = $this->getDictionaryConfig($dictionary);
        $model = $config['model'];

        $item = $model::findOrFail($id);

        $rules = [];
        foreach ($config['fields'] as $field => $fieldConfig) {
            if ($fieldConfig['required']) {
                $rules[$field] = 'required';
            }
        }

        $validated = $request->validate($rules);

        // Auto-generate slug if not provided and field exists
        if (isset($config['fields']['slug']) && empty($validated['slug'] ?? null) && isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $item->update($validated);

        return response()->json([
            'item' => $item->fresh(),
        ]);
    }

    /**
     * Remove the specified item.
     */
    public function destroy(Request $request, $dictionary, $id)
    {
        $config = $this->getDictionaryConfig($dictionary);
        $model = $config['model'];

        $item = $model::findOrFail($id);
        $item->delete();

        return response()->json(null, 204);
    }

    /**
     * Get available dictionaries
     */
    public function dictionaries()
    {
        return response()->json([
            'dictionaries' => array_keys($this->dictionaries),
        ]);
    }
}
