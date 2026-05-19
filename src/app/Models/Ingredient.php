<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price_per_gram',
        'calories_per_gram',
        'is_active',
    ];

    protected $casts = [
        'price_per_gram' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class, 'pizza_ingredient')
            ->withPivot('quantity_in_grams')
            ->withTimestamps();
    }
}
