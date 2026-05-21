<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePizzaRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'base_ingredient_id' => 'required|exists:ingredients,id',
            'sauce_ingredient_id' => 'required|exists:ingredients,id',
            'spiciness' => 'required|in:not_spicy,moderately_spicy,spicy,very_spicy',
            'is_vegan' => 'nullable|boolean',
            'price' => 'nullable|numeric|min:0',
            'selling_price' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }
}
