<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            InspectionPackageSeeder::class,
            InspectionChecklistItemSeeder::class,
            VehicleSeeder::class,
            TariffSeeder::class,
            BookingSeeder::class,
            CmsContentSeeder::class,
        ]);
    }
}
