<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Pizza extends Model
{
    use HasTranslations;

    protected $fillable = [
        'name',
        'description',
        'base_ingredient_id',
        'sauce_ingredient_id',
        'spiciness',
        'is_vegan',
        'price',
        'selling_price',
        'is_active',
    ];

    protected $translatable = [
        'name',
        'description',
    ];

    protected $casts = [
        'is_vegan' => 'boolean',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    protected $with = ['baseIngredient', 'sauceIngredient'];

    public function baseIngredient()
    {
        return $this->belongsTo(Ingredient::class, 'base_ingredient_id');
    }

    public function sauceIngredient()
    {
        return $this->belongsTo(Ingredient::class, 'sauce_ingredient_id');
    }

    public function ingredients()
    {
        return $this->belongsToMany(Ingredient::class, 'pizza_ingredient')
                    ->withPivot('quantity_in_grams')
                    ->withTimestamps();
    }

    /**
     * Calculate total calories for the pizza
     * Sum of all ingredient calories based on their quantities in grams
     */
    public function calculateCalories(): float
    {
        $this->load(['ingredients', 'baseIngredient', 'sauceIngredient']);

        $totalCalories = 0;

        foreach ($this->ingredients as $ingredient) {
            $quantityInGrams = $ingredient->pivot->quantity_in_grams ?? 0;
            $caloriesPerGram = $ingredient->calories_per_gram ?? 0;
            $totalCalories += ($caloriesPerGram * $quantityInGrams);
        }

        return $totalCalories;
    }

    /**
     * Calculate total cost of the pizza based on ingredient quantities
     * Note: This requires price information from stock movements
     * For now, this is a placeholder - actual implementation would need
     * average cost calculation from stock movements
     */
    public function calculateCost(): float
    {
        $this->load(['ingredients']);

        $totalCost = 0;

        foreach ($this->ingredients as $ingredient) {
            $quantityInGrams = $ingredient->pivot->quantity_in_grams ?? 0;
            // TODO: Implement average cost calculation from stock movements
            // For now, return 0 as placeholder
            $totalCost += 0;
        }

        return $totalCost;
    }

    /**
     * Calculate profit margin
     */
    public function calculateProfitMargin(): ?float
    {
        if (!$this->selling_price || $this->selling_price <= 0) {
            return null;
        }

        $cost = $this->calculateCost();
        if ($cost <= 0) {
            return null;
        }

        return (($this->selling_price - $cost) / $this->selling_price) * 100;
    }

    /**
     * Scope to filter only active pizzas
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by spiciness
     */
    public function scopeOfSpiciness(Builder $query, string $spiciness): Builder
    {
        return $query->where('spiciness', $spiciness);
    }

    /**
     * Scope to filter vegan pizzas
     */
    public function scopeVegan(Builder $query): Builder
    {
        return $query->where('is_vegan', true);
    }

    /**
     * Accessor for formatted calories
     */
    public function getFormattedCaloriesAttribute(): string
    {
        return number_format($this->calculateCalories(), 2) . ' kcal';
    }

    /**
     * Accessor for formatted profit
     */
    public function getFormattedProfitAttribute(): string
    {
        $profit = $this->selling_price - $this->calculateCost();
        return number_format($profit, 2) . ' €';
    }
}
