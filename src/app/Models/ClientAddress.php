<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Model;

class ClientAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'client_id',
        'address_line_1',
        'address_line_2',
        'city',
        'postal_code',
        'country',
        'is_default',
        'delivery_instructions',
        'contact_phone',
        'address_type',
    ];

    protected $casts = [
        'is_default' => 'boolean',
    ];

    // Relationships
    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    // Scopes
    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('address_type', $type);
    }
}
