<?php

namespace App\Models;

use App\Models\Traits\HasTranslations;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Ingredient extends Model
{
    use HasTranslations;

    protected $fillable = [
        'category',
        'name',
        'description',
        'calories_per_gram',
        'is_active',
    ];

    protected $translatable = [
        'name',
        'description',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'calories_per_gram' => 'decimal:2',
    ];

    public function pizzas()
    {
        return $this->belongsToMany(Pizza::class, 'pizza_ingredient')
            ->withPivot('quantity_in_grams')
            ->withTimestamps();
    }

    /**
     * Scope to filter only active ingredients
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to filter by category
     */
    public function scopeOfCategory(Builder $query, string $category): Builder
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by multiple categories
     */
    public function scopeInCategories(Builder $query, array $categories): Builder
    {
        return $query->whereIn('category', $categories);
    }

    /**
     * Accessor for formatted calories
     */
    public function getFormattedCaloriesAttribute(): string
    {
        return number_format($this->calories_per_gram, 2) . ' kcal/100g';
    }
}
