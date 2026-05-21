<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovement extends Model
{
    protected $fillable = [
        'ingredient_id',
        'type',
        'quantity',
        'date',
        'notes',
        'reference',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'date' => 'date',
    ];

    public function ingredient()
    {
        return $this->belongsTo(Ingredient::class);
    }

    /**
     * Calculate current stock for an ingredient
     */
    public static function calculateStock($ingredientId): float
    {
        $inbound = self::where('ingredient_id', $ingredientId)
            ->where('type', 'inbound')
            ->sum('quantity');

        $outbound = self::where('ingredient_id', $ingredientId)
            ->where('type', 'outbound')
            ->sum('quantity');

        return $inbound - $outbound;
    }

    /**
     * Scope to filter by type
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to filter by ingredient
     */
    public function scopeForIngredient($query, $ingredientId)
    {
        return $query->where('ingredient_id', $ingredientId);
    }
}
