<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TariffSeeder extends Seeder
{
    public function run(): void
    {
        $now   = now();
        $today = now()->toDateString();

        $tariffs = [
            // ── Jasa ─────────────────────────────────────────────
            ['name' => 'Jasa Ganti Oli',        'category' => 'Jasa',      'price' => 50000,  'unit' => 'kali'],
            ['name' => 'Jasa Tune Up',           'category' => 'Jasa',      'price' => 150000, 'unit' => 'kali'],
            ['name' => 'Jasa Ganti Ban',         'category' => 'Jasa',      'price' => 50000,  'unit' => 'buah'],
            ['name' => 'Jasa Ganti Kampas Rem',  'category' => 'Jasa',      'price' => 75000,  'unit' => 'set'],
            ['name' => 'Jasa Balancing',         'category' => 'Jasa',      'price' => 40000,  'unit' => 'ban'],
            ['name' => 'Jasa Spooring',          'category' => 'Jasa',      'price' => 100000, 'unit' => 'kali'],
            ['name' => 'Jasa Servis AC',         'category' => 'Jasa',      'price' => 200000, 'unit' => 'kali'],
            ['name' => 'Jasa Scan Diagnostic',   'category' => 'Jasa',      'price' => 100000, 'unit' => 'kali'],
            // ── Oli ──────────────────────────────────────────────
            ['name' => 'Oli Mesin 10W-40 (1L)',  'category' => 'Oli',       'price' => 65000,  'unit' => 'liter'],
            ['name' => 'Oli Mesin 5W-30 Synthetic','category'=> 'Oli',      'price' => 95000,  'unit' => 'liter'],
            ['name' => 'Oli Transmisi Matic',    'category' => 'Oli',       'price' => 80000,  'unit' => 'liter'],
            ['name' => 'Minyak Rem DOT 4',       'category' => 'Oli',       'price' => 30000,  'unit' => 'botol'],
            // ── Sparepart ─────────────────────────────────────────
            ['name' => 'Filter Oli',             'category' => 'Sparepart', 'price' => 45000,  'unit' => 'pcs'],
            ['name' => 'Filter Udara',           'category' => 'Sparepart', 'price' => 85000,  'unit' => 'pcs'],
            ['name' => 'Busi NGK (set 4)',        'category' => 'Sparepart', 'price' => 120000, 'unit' => 'set'],
            ['name' => 'Kampas Rem Depan',       'category' => 'Sparepart', 'price' => 185000, 'unit' => 'set'],
            ['name' => 'Kampas Rem Belakang',    'category' => 'Sparepart', 'price' => 145000, 'unit' => 'set'],
            ['name' => 'Ban 185/65 R15',         'category' => 'Sparepart', 'price' => 750000, 'unit' => 'buah'],
            ['name' => 'Ban 195/65 R15',         'category' => 'Sparepart', 'price' => 850000, 'unit' => 'buah'],
            ['name' => 'Aki Kering 45Ah',        'category' => 'Sparepart', 'price' => 550000, 'unit' => 'pcs'],
            ['name' => 'Wiper Depan',            'category' => 'Sparepart', 'price' => 95000,  'unit' => 'pcs'],
            ['name' => 'Timing Belt / V-Belt',   'category' => 'Sparepart', 'price' => 250000, 'unit' => 'set'],
        ];

        $rows = [];
        foreach ($tariffs as $t) {
            $rows[] = [
                'name'         => $t['name'],
                'category'     => $t['category'],
                'price'        => $t['price'],
                'unit'         => $t['unit'],
                'description'  => null,
                'active_from'  => $today,
                'active_until' => null,
                'is_active'    => true,
                'created_at'   => $now,
                'updated_at'   => $now,
            ];
        }

        DB::table('tariffs')->insert($rows);
    }
}
