<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class InspectionChecklistItem extends Model
{
    use HasFactory;

    protected $table = 'inspection_checklist_item';

    protected $fillable = [
        'package_id', 'item_name', 'category', 'description', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Opsi jawaban yang valid untuk setiap poin checklist.
     * Nilai ini dipakai di form inspector, validasi controller, dan laporan.
     */
    public static array $statusOptions = [
        'ok'              => 'OK',
        'perlu_diservis'  => 'Perlu Diservis',
        'perlu_diganti'   => 'Perlu Diganti',
        'tidak_tersedia'  => 'Tidak Tersedia',
    ];

    /**
     * Mapping status lama (3 opsi) ke status baru (4 opsi).
     * Dipakai saat memuat hasil inspeksi lama.
     */
    public static array $legacyStatusMap = [
        'baik'  => 'ok',
        'cukup' => 'perlu_diservis',
        'buruk' => 'perlu_diganti',
    ];

    // ── Scopes ───────────────────────────────────────────────────

    /** Hanya item yang aktif (poin baru). */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    // ── Relations ─────────────────────────────────────────────────

    /** Relasi ke paket — nullable karena poin baru tidak terikat paket. */
    public function package()
    {
        return $this->belongsTo(InspectionPackage::class, 'package_id');
    }
}

