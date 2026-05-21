<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreIngredientRequest extends FormRequest
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
            'category' => 'required|in:base,sauce,filling',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'calories_per_gram' => 'nullable|numeric|min:0',
            'is_active' => 'nullable|boolean',
        ];
    }
}
