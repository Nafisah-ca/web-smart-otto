<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'description', 'price', 'duration_estimate',
        'icon', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'price'     => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function checklistItems()
    {
        return $this->hasMany(InspectionChecklistItem::class, 'package_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }
}
