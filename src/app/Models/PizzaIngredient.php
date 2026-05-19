<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class PizzaIngredient extends Pivot
{
    protected $table = 'pizza_ingredient';

    protected $fillable = [
        'pizza_id',
        'ingredient_id',
        'quantity_in_grams',
    ];

    protected $casts = [
        'quantity_in_grams' => 'decimal:2',
    ];

    public function pizza()
    {
        return $this->belongsTo(Pizza::class);
    }

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }
}
