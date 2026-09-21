<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InspectionChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        // ── Paket 1: Basic ─────────────────────────────────────────
        $basic = [
            ['category' => 'Eksterior',  'item_name' => 'Kondisi Body Eksterior',       'sort_order' => 1],
            ['category' => 'Eksterior',  'item_name' => 'Kondisi Kaca Depan & Belakang','sort_order' => 2],
            ['category' => 'Ban & Roda', 'item_name' => 'Tekanan & Kondisi Ban',        'sort_order' => 3],
            ['category' => 'Ban & Roda', 'item_name' => 'Kondisi Velg',                 'sort_order' => 4],
            ['category' => 'Rem',        'item_name' => 'Rem Depan',                    'sort_order' => 5],
            ['category' => 'Rem',        'item_name' => 'Rem Belakang',                 'sort_order' => 6],
            ['category' => 'Lampu',      'item_name' => 'Lampu Depan (Utama & Sein)',   'sort_order' => 7],
            ['category' => 'Lampu',      'item_name' => 'Lampu Belakang & Stop',        'sort_order' => 8],
        ];

        // ── Paket 2: Standar (basic + tambahan) ───────────────────
        $standarExtra = [
            ['category' => 'Mesin',       'item_name' => 'Kondisi Mesin (Visual)',       'sort_order' => 9],
            ['category' => 'Mesin',       'item_name' => 'Level & Kondisi Oli Mesin',    'sort_order' => 10],
            ['category' => 'Mesin',       'item_name' => 'Kondisi Aki / Baterai',        'sort_order' => 11],
            ['category' => 'Mesin',       'item_name' => 'Sistem Pendingin / Radiator',  'sort_order' => 12],
            ['category' => 'Transmisi',   'item_name' => 'Kondisi Transmisi',            'sort_order' => 13],
            ['category' => 'Transmisi',   'item_name' => 'Level Oli Transmisi',          'sort_order' => 14],
            ['category' => 'Kaki-kaki',   'item_name' => 'Kondisi Suspensi Depan',       'sort_order' => 15],
            ['category' => 'Kaki-kaki',   'item_name' => 'Kondisi Suspensi Belakang',    'sort_order' => 16],
            ['category' => 'Kaki-kaki',   'item_name' => 'Tie Rod & Ball Joint',         'sort_order' => 17],
            ['category' => 'Kelistrikan', 'item_name' => 'Sistem Kelistrikan Umum',      'sort_order' => 18],
            ['category' => 'AC',          'item_name' => 'Kondisi & Performa AC',        'sort_order' => 19],
            ['category' => 'Interior',    'item_name' => 'Kondisi Dashboard & Interior', 'sort_order' => 20],
        ];

        // ── Paket 3: Premium (standar + tambahan) ─────────────────
        $premiumExtra = [
            ['category' => 'Mesin',      'item_name' => 'Scan Diagnostic ECU',          'sort_order' => 21],
            ['category' => 'Mesin',      'item_name' => 'Kondisi Busi & Filter Udara',  'sort_order' => 22],
            ['category' => 'Mesin',      'item_name' => 'Uji Emisi Gas Buang',          'sort_order' => 23],
            ['category' => 'Transmisi',  'item_name' => 'Uji Kopling (Manual)',          'sort_order' => 24],
            ['category' => 'Rem',        'item_name' => 'Level & Kondisi Minyak Rem',   'sort_order' => 25],
            ['category' => 'Kaki-kaki',  'item_name' => 'Kondisi Bearing Roda',         'sort_order' => 26],
            ['category' => 'Kaki-kaki',  'item_name' => 'Spooring & Balancing',         'sort_order' => 27],
            ['category' => 'Kelistrikan','item_name' => 'Power Window & Central Lock',  'sort_order' => 28],
            ['category' => 'Kelistrikan','item_name' => 'Sistem Audio & Hiburan',       'sort_order' => 29],
            ['category' => 'Test Drive', 'item_name' => 'Test Drive & Handling',        'sort_order' => 30],
        ];

        $rows = [];

        foreach ($basic as $item) {
            $rows[] = array_merge($item, ['package_id' => 1, 'description' => null, 'created_at' => $now, 'updated_at' => $now]);
        }

        foreach (array_merge($basic, $standarExtra) as $item) {
            $rows[] = array_merge($item, ['package_id' => 2, 'description' => null, 'created_at' => $now, 'updated_at' => $now]);
        }

        foreach (array_merge($basic, $standarExtra, $premiumExtra) as $item) {
            $rows[] = array_merge($item, ['package_id' => 3, 'description' => null, 'created_at' => $now, 'updated_at' => $now]);
        }

        DB::table('inspection_checklist_items')->insert($rows);
    }
}
