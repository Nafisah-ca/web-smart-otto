<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tariff extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'price', 'unit',
        'description', 'active_from', 'active_until', 'is_active',
    ];

    protected $casts = [
        'price'        => 'decimal:2',
        'active_from'  => 'date',
        'active_until' => 'date',
        'is_active'    => 'boolean',
    ];

    public function transactionItems()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('active_from', '<=', now()->toDateString())
            ->where(function ($q) {
                $q->whereNull('active_until')
                  ->orWhere('active_until', '>=', now()->toDateString());
            });
    }
}
