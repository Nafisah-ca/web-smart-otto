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

        foreach ($tariffs as $t) {
            DB::table('tariff')->updateOrInsert(
                ['name' => $t['name']],
                [
                    'category'     => $t['category'],
                    'price'        => $t['price'],
                    'unit'         => $t['unit'],
                    'description'  => null,
                    'active_from'  => $today,
                    'active_until' => null,
                    'is_active'    => true,
                    'updated_at'   => now(),
                ]
            );
        }

        $this->command->info('TariffSeeder: ' . count($tariffs) . ' tarif berhasil di-seed (idempotent).');
    }
}
