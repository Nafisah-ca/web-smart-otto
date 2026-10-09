<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class VehicleCategory extends Model
{
    protected $table = 'vehicle_category';

    protected $fillable = [
        'name', 'description', 'price_min', 'price_max', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_min' => 'decimal:2',
            'price_max' => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    // ── Accessors ─────────────────────────────────────────────────

    /** "Rp 350.000 - 500.000,-" */
    public function getFormattedRangeAttribute(): string
    {
        return 'Rp ' . number_format($this->price_min, 0, ',', '.')
             . ' - ' . number_format($this->price_max, 0, ',', '.') . ',-';
    }

    // ── Scopes ────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    // ── Relations ─────────────────────────────────────────────────

    public function modelKeywords()
    {
        return $this->hasMany(VehicleCategoryModel::class, 'category_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'category_id');
    }

    // ── Static Helper ─────────────────────────────────────────────

    /**
     * Cari kategori yang cocok berdasarkan nama model kendaraan (fuzzy LIKE).
     * Return: [VehicleCategory|null, bool $ambiguous]
     *   - null, false  → tidak ada yang cocok
     *   - $cat, false  → tepat satu cocok
     *   - null, true   → lebih dari satu cocok (admin pilih manual)
     */
    public static function detectFromModel(string $vehicleModel): array
    {
        if (blank($vehicleModel)) {
            return [null, false];
        }

        $keyword = strtolower(trim($vehicleModel));

        // Ambil semua keyword aktif sekaligus untuk efisiensi
        $rows = \DB::table('vehicle_category_model as vcm')
            ->join('vehicle_category as vc', 'vc.id', '=', 'vcm.category_id')
            ->where('vc.is_active', true)
            ->whereRaw('LOWER(?) LIKE CONCAT("%", LOWER(vcm.model_keyword), "%")', [$keyword])
            ->select('vc.id', 'vc.name', 'vcm.model_keyword')
            ->orderBy('vc.sort_order')
            ->get();

        if ($rows->isEmpty()) {
            return [null, false];
        }

        // Ambil kategori unik yang cocok
        $categoryIds = $rows->pluck('id')->unique()->values();

        if ($categoryIds->count() > 1) {
            // Lebih dari satu kategori cocok → ambiguous
            return [null, true];
        }

        $category = static::find($categoryIds->first());
        return [$category, false];
    }
}
