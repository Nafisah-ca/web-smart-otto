<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transaction';

    protected $fillable = [
        'transaction_code', 'booking_id', 'subtotal', 'tax',
        'discount', 'total', 'payment_status', 'payment_method',
        'payment_channel', 'payment_proof', 'payment_reference',
        'paid_at', 'notes', 'admin_notes',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'tax'      => 'decimal:2',
        'discount' => 'decimal:2',
        'total'    => 'decimal:2',
        'paid_at'  => 'datetime',
    ];

    public static array $paymentStatusLabels = [
        'unpaid'   => 'Belum Bayar',
        'pending'  => 'Menunggu Verifikasi',
        'partial'  => 'Bayar Sebagian',
        'paid'     => 'Lunas',
        'rejected' => 'Bukti Ditolak',
    ];

    public static array $paymentStatusColors = [
        'unpaid'   => 'red',
        'pending'  => 'yellow',
        'partial'  => 'blue',
        'paid'     => 'green',
        'rejected' => 'red',
    ];

    public function getPaymentStatusLabelAttribute(): string
    {
        return self::$paymentStatusLabels[$this->payment_status] ?? ucfirst($this->payment_status);
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return self::$paymentStatusColors[$this->payment_status] ?? 'gray';
    }

    public function isPaid(): bool
    {
        return $this->payment_status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->payment_status === 'pending';
    }

    public function isUnpaid(): bool
    {
        return $this->payment_status === 'unpaid';
    }

    public function getPaymentProofUrlAttribute(): ?string
    {
        if (!$this->payment_proof) {
            return null;
        }

        if (str_starts_with($this->payment_proof, 'http')) {
            return $this->payment_proof;
        }

        return asset($this->payment_proof);
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

    public function getFormattedSubtotalAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal, 0, ',', '.');
    }

    public function getFormattedTaxAttribute(): string
    {
        return 'Rp ' . number_format($this->tax, 0, ',', '.');
    }

    public function getFormattedDiscountAttribute(): string
    {
        return 'Rp ' . number_format($this->discount, 0, ',', '.');
    }

    public static function generateCode(): string
    {
        $prefix = 'TRX-' . date('Ymd') . '-';
        $last   = self::where('transaction_code', 'like', $prefix . '%')
                      ->orderByDesc('id')->first();
        $number = $last ? (int) substr($last->transaction_code, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function recalculate(): void
    {
        $subtotal = (float) $this->items()->sum('subtotal');
        $tax      = round($subtotal * 0.11); // PPN 11%
        $discount = (float) ($this->discount ?? 0);
        $total    = max(0, $subtotal + $tax - $discount);

        $this->update([
            'subtotal' => $subtotal,
            'tax'      => $tax,
            'discount' => $discount,
            'total'    => $total,
        ]);
    }
}
