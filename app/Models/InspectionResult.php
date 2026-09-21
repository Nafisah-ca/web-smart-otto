<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InspectionResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id', 'checklist_json', 'condition_summary',
        'recommendation', 'photos', 'inspector_notes',
        'is_verified', 'verified_at', 'completed_at',
        'customer_signature', 'signed_at',
    ];

    protected $casts = [
        'checklist_json' => 'array',
        'photos'         => 'array',
        'is_verified'    => 'boolean',
        'verified_at'    => 'datetime',
        'completed_at'   => 'datetime',
        'signed_at'      => 'datetime',
    ];

    public static array $conditionLabels = [
        'baik'             => 'Baik',
        'cukup'            => 'Cukup',
        'perlu_perhatian'  => 'Perlu Perhatian',
        'kritis'           => 'Kritis',
    ];

    public static array $conditionColors = [
        'baik'             => 'green',
        'cukup'            => 'blue',
        'perlu_perhatian'  => 'yellow',
        'kritis'           => 'red',
    ];

    public function getConditionLabelAttribute(): string
    {
        return self::$conditionLabels[$this->condition_summary] ?? '-';
    }

    public function getConditionColorAttribute(): string
    {
        return self::$conditionColors[$this->condition_summary] ?? 'gray';
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
