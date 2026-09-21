<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_code', 'booking_id', 'subtotal', 'tax',
        'discount', 'total', 'payment_status', 'payment_method',
        'paid_at', 'notes',
    ];

    protected $casts = [
        'subtotal'       => 'decimal:2',
        'tax'            => 'decimal:2',
        'discount'       => 'decimal:2',
        'total'          => 'decimal:2',
        'paid_at'        => 'datetime',
    ];

    public static array $paymentStatusLabels = [
        'unpaid'  => 'Belum Bayar',
        'partial' => 'Bayar Sebagian',
        'paid'    => 'Lunas',
    ];

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::$paymentStatusLabels[$this->payment_status] ?? $this->payment_status;
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }

    public function getFormattedTotalAttribute(): string
    {
        return 'Rp ' . number_format($this->total, 0, ',', '.');
    }

    public static function generateCode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = self::where('transaction_code', 'like', $prefix . '%')
                      ->orderByDesc('transaction_code')->first();
        $number = $last ? (int) substr($last->transaction_code, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function recalculate(): void
    {
        $subtotal = $this->items()->sum('subtotal');
        $tax      = round($subtotal * 0.11); // PPN 11%
        $total    = $subtotal + $tax - $this->discount;

        $this->update([
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'total'    => $total,
        ]);
    }
}
