<?php

namespace Database\Seeders;

use App\Models\VehicleCategory;
use App\Models\VehicleCategoryModel;
use Illuminate\Database\Seeder;

/**
 * VehicleCategorySeeder
 *
 * Data kategori kendaraan sesuai screenshot halaman "Pilih Kategori".
 * Idempotent: updateOrCreate by name untuk kategori,
 *             cek exists per keyword sebelum insert model.
 */
class VehicleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name'        => 'Kat. 1',
                'description' => 'Kendaraan premium, SUV besar, dan mobil Eropa/Amerika',
                'price_min'   => 500000,
                'price_max'   => 800000,
                'sort_order'  => 1,
                'models'      => [
                    'Alphard', 'Land Cruiser', 'Palisade', 'Mazda CX-8',
                    'Sedan Mercy', 'Sedan BMW', 'Mini Cooper', 'Hilux',
                    'Triton', 'Range Rover', 'All Lexus', 'Haice',
                    'Mazda CX-60', 'Chevrolet Captiva', 'SUV Mercy', 'SUV BMW',
                    'Rubicon', 'Navara', 'Ford Ranger', 'City Car Eropa',
                ],
            ],
            [
                'name'        => 'Kat. 2',
                'description' => 'SUV menengah, MPV, dan sedan Jepang',
                'price_min'   => 350000,
                'price_max'   => 500000,
                'sort_order'  => 2,
                'models'      => [
                    'Fortuner', 'Pajero', 'Everest', 'Rush',
                    'Terios', 'Innova', 'Kijang', 'Avanza',
                    'Xenia', 'CX-5', 'HR-V', 'CR-V',
                    'X-Trail', 'Outlander', 'Ertiga', 'XL7',
                    'Livina', 'Freed', 'Jazz', 'Yaris',
                ],
            ],
            [
                'name'        => 'Kat. 3',
                'description' => 'City car dan LCGC',
                'price_min'   => 250000,
                'price_max'   => 350000,
                'sort_order'  => 3,
                'models'      => [
                    'Brio', 'Agya', 'Ayla', 'Calya',
                    'Sigra', 'March', 'Mirage', 'Picanto',
                    'Ignis', 'S-Cross', 'Swift', 'Baleno',
                    'Karimun', 'Wagon R', 'Splash', 'Alto',
                    'Celerio', 'Sirion', 'Veloz', 'Xpander',
                ],
            ],
        ];

        foreach ($categories as $data) {
            $models = $data['models'];
            unset($data['models']);

            // Upsert kategori
            $category = VehicleCategory::updateOrCreate(
                ['name' => $data['name']],
                array_merge($data, ['is_active' => true])
            );

            // Insert keyword model yang belum ada
            foreach ($models as $keyword) {
                $exists = VehicleCategoryModel::where('category_id', $category->id)
                    ->where('model_keyword', $keyword)
                    ->exists();

                if (! $exists) {
                    VehicleCategoryModel::create([
                        'category_id'   => $category->id,
                        'model_keyword' => $keyword,
                    ]);
                }
            }
        }

        $this->command->info('VehicleCategorySeeder: 3 kategori + model keyword berhasil di-seed.');
    }
}
