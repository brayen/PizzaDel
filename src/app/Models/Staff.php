<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'position',
        'password',
        'hire_date',
        'salary',
        'work_schedule',
        'is_active',
        'responsibilities',
        'emergency_contact_name',
        'emergency_contact_phone',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date' => 'date',
        'is_active' => 'boolean',
        'salary' => 'integer',
        'work_schedule' => 'array',
        'responsibilities' => 'array',
        'last_login_at' => 'datetime',
    ];

    // Relationships
    public function processedOrders()
    {
        return $this->hasMany(Order::class, 'processed_by_staff_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByPosition($query, $position)
    {
        return $query->where('position', $position);
    }

    public function scopeManagers($query)
    {
        return $query->whereIn('position', ['admin', 'manager']);
    }

    // Accessors
    public function getIsAdminAttribute()
    {
        return in_array($this->position, ['admin', 'manager']);
    }

    public function getFormattedSalaryAttribute()
    {
        return '€' . number_format($this->salary / 100, 2);
    }

    public function getSalaryEurosAttribute()
    {
        return $this->salary / 100;
    }

    // Helper method to convert euros to cents
    public static function eurosToCents($euros)
    {
        return (int) round($euros * 100);
    }

    public function getDaysOfServiceAttribute()
    {
        return $this->hire_date ? $this->hire_date->diffInDays(now()) : 0;
    }
}
