<?php

namespace Database\Seeders;

use App\Models\InspectionPackage;
use Illuminate\Database\Seeder;

class InspectionPackageSeeder extends Seeder
{
    public function run(): void
    {
        InspectionPackage::insert([
            [
                'name'              => 'Inspeksi Basic',
                'description'       => 'Pemeriksaan dasar kendaraan meliputi kondisi eksterior, ban, rem, dan lampu. Cocok untuk pengecekan rutin harian.',
                'price'             => 150000,
                'duration_estimate' => 60,
                'icon'              => '🔍',
                'is_active'         => true,
                'sort_order'        => 1,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Inspeksi Standar',
                'description'       => 'Pemeriksaan menyeluruh meliputi mesin, transmisi, sistem kelistrikan, kaki-kaki, rem, AC, dan eksterior/interior.',
                'price'             => 350000,
                'duration_estimate' => 120,
                'icon'              => '🔧',
                'is_active'         => true,
                'sort_order'        => 2,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
            [
                'name'              => 'Inspeksi Premium',
                'description'       => 'Pemeriksaan komprehensif 150 titik meliputi seluruh komponen kendaraan termasuk scan ECU, uji emisi, dan test drive.',
                'price'             => 650000,
                'duration_estimate' => 180,
                'icon'              => '⭐',
                'is_active'         => true,
                'sort_order'        => 3,
                'created_at'        => now(),
                'updated_at'        => now(),
            ],
        ]);
    }
}
