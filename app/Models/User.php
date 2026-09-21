<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'phone', 'password', 'role', 'avatar', 'address',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Scopes
    public function scopeCustomers($query) { return $query->where('role', 'customer'); }
    public function scopeAdmins($query)    { return $query->where('role', 'admin'); }
    public function scopeInspectors($query){ return $query->where('role', 'inspector'); }

    // Helpers
    public function isAdmin(): bool    { return $this->role === 'admin'; }
    public function isInspector(): bool{ return $this->role === 'inspector'; }
    public function isCustomer(): bool { return $this->role === 'customer'; }

    // Relationships
    public function vehicles()
    {
        return $this->hasMany(Vehicle::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function assignedBookings()
    {
        return $this->hasMany(Booking::class, 'inspector_id');
    }
}
