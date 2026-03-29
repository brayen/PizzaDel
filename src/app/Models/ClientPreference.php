<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ClientPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'birth_date',
        'gender',
        'pizza_preferences',
        'dietary_restrictions',
        'email_notifications',
        'sms_notifications',
        'promotional_emails',
        'loyalty_points',
        'discount_level',
        'total_spent',
        'total_orders',
        'last_order_at',
        'preferred_payment_method',
        'preferred_delivery_time',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'email_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'promotional_emails' => 'boolean',
        'pizza_preferences' => 'array',
        'dietary_restrictions' => 'array',
        'loyalty_points' => 'integer',
        'total_spent' => 'integer',
        'total_orders' => 'integer',
        'last_order_at' => 'datetime',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Scopes
    public function scopeByDiscountLevel($query, $level)
    {
        return $query->where('discount_level', $level);
    }

    public function scopeWithNotifications($query)
    {
        return $query->where('email_notifications', true)
                    ->orWhere('sms_notifications', true);
    }

    // Accessors
    public function getLoyaltyProgressAttribute()
    {
        $levels = [
            'bronze' => ['min' => 0, 'next' => 500],
            'silver' => ['min' => 500, 'next' => 1500],
            'gold' => ['min' => 1500, 'next' => 3000],
            'platinum' => ['min' => 3000, 'next' => null],
        ];

        $current = $this->discount_level;
        $spent = $this->total_spent;
        
        if (isset($levels[$current])) {
            $level = $levels[$current];
            $progress = min(($spent - $level['min']) / ($level['next'] - $level['min']) * 100, 100);
            
            return [
                'current' => $current,
                'spent' => $spent,
                'min_for_level' => $level['min'],
                'next_level_at' => $level['next'],
                'progress' => $progress,
            ];
        }

        return ['current' => 'bronze', 'spent' => $spent, 'progress' => 0];
    }

    // Money accessors (convert cents to euros)
    public function getTotalSpentEurosAttribute()
    {
        return $this->total_spent / 100;
    }

    public function getLoyaltyPointsEurosAttribute()
    {
        return $this->loyalty_points / 100;
    }

    // Helper method to convert euros to cents
    public static function eurosToCents($euros)
    {
        return (int) round($euros * 100);
    }
}
