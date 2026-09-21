<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'user_id', 'vehicle_id', 'package_id', 'inspector_id',
        'booking_date', 'booking_time', 'status', 'notes',
        'cancellation_reason', 'confirmed_at',
    ];

    protected $casts = [
        'booking_date'  => 'date',
        'confirmed_at'  => 'datetime',
    ];

    // Status labels
    public static array $statusLabels = [
        'pending'     => 'Menunggu Konfirmasi',
        'confirmed'   => 'Dikonfirmasi',
        'waiting'     => 'Menunggu Layanan',
        'on_progress' => 'Sedang Dikerjakan',
        'completed'   => 'Selesai',
        'cancelled'   => 'Dibatalkan',
    ];

    public static array $statusColors = [
        'pending'     => 'yellow',
        'confirmed'   => 'blue',
        'waiting'     => 'purple',
        'on_progress' => 'orange',
        'completed'   => 'green',
        'cancelled'   => 'red',
    ];

    public function getStatusLabelAttribute(): string
    {
        return self::$statusLabels[$this->status] ?? $this->status;
    }

    public function getStatusColorAttribute(): string
    {
        return self::$statusColors[$this->status] ?? 'gray';
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function package()
    {
        return $this->belongsTo(InspectionPackage::class, 'package_id');
    }

    public function inspector()
    {
        return $this->belongsTo(User::class, 'inspector_id');
    }

    public function inspectionResult()
    {
        return $this->hasOne(InspectionResult::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Generate booking code
    public static function generateCode(): string
    {
        $prefix = 'SO-' . date('Ymd') . '-';
        $last   = self::where('booking_code', 'like', $prefix . '%')
                      ->orderByDesc('booking_code')->first();
        $number = $last ? (int) substr($last->booking_code, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
