<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\InspectionResult;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\InspectionPackage;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * DemoBookingSeeder
 *
 * Membuat data contoh untuk pengujian SEBELUM perubahan besar (Tahap 3).
 * Tujuan: verifikasi laporan lama masih tampil normal setelah refactor.
 *
 * Data yang dibuat:
 * - 3 customer demo
 * - 3 kendaraan demo (Avanza, Fortuner, Brio — mewakili Kat.2, Kat.2, Kat.3)
 * - 3 booking SELESAI (completed + verified) dengan checklist_json format LAMA
 *   (status: baik / cukup / buruk — untuk uji fallback mapping)
 * - 2 booking status lain (pending, on_progress)
 *
 * Idempotent: dicek via booking_code sebelum insert.
 */
class DemoBookingSeeder extends Seeder
{
    public function run(): void
    {
        // ── Ambil inspektor yang sudah ada ────────────────────────
        $inspector1 = User::where('email', 'inspektor1@smartotto.test')->first();
        $inspector2 = User::where('email', 'inspektor2@smartotto.test')->first();

        if (! $inspector1 || ! $inspector2) {
            $this->command->warn('Inspektor belum ada. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        // ── Ambil salah satu paket aktif (untuk FK booking lama) ──
        $package = InspectionPackage::where('is_active', true)->orderBy('id')->first()
                ?? InspectionPackage::orderBy('id')->first();

        if (! $package) {
            $this->command->warn('Paket inspeksi belum ada. Jalankan InspectionPackageSeeder terlebih dahulu.');
            return;
        }

        // ── Buat customer demo ─────────────────────────────────────
        $customers = [
            User::firstOrCreate(['email' => 'budi@demo.test'], [
                'name'     => 'Budi Santoso',
                'phone'    => '081234567890',
                'password' => Hash::make('Demo@2026'),
                'role'     => 'customer',
            ]),
            User::firstOrCreate(['email' => 'siti@demo.test'], [
                'name'     => 'Siti Rahayu',
                'phone'    => '081234567891',
                'password' => Hash::make('Demo@2026'),
                'role'     => 'customer',
            ]),
            User::firstOrCreate(['email' => 'andi@demo.test'], [
                'name'     => 'Andi Wijaya',
                'phone'    => '081234567892',
                'password' => Hash::make('Demo@2026'),
                'role'     => 'customer',
            ]),
        ];

        // ── Buat kendaraan demo ────────────────────────────────────
        $vehicles = [
            Vehicle::firstOrCreate(
                ['plate_number' => 'B 1234 DEM', 'user_id' => $customers[0]->id],
                ['brand' => 'Toyota', 'model' => 'Avanza', 'year' => 2019, 'type' => 'mobil', 'color' => 'Putih']
            ),
            Vehicle::firstOrCreate(
                ['plate_number' => 'B 5678 DEM', 'user_id' => $customers[1]->id],
                ['brand' => 'Toyota', 'model' => 'Fortuner', 'year' => 2020, 'type' => 'mobil', 'color' => 'Hitam']
            ),
            Vehicle::firstOrCreate(
                ['plate_number' => 'B 9012 DEM', 'user_id' => $customers[2]->id],
                ['brand' => 'Honda', 'model' => 'Brio', 'year' => 2021, 'type' => 'mobil', 'color' => 'Merah']
            ),
        ];

        // ── 81 poin checklist format LAMA (baik/cukup/buruk) ───────
        // Pakai 10 poin generik saja untuk simulasi data lama
        $checklistLama = function (string $kondisi1, string $kondisi2, string $kondisi3) {
            $poin = [
                ['item_id' => 1, 'item' => 'Kondisi Body Eksterior',        'category' => 'Eksterior'],
                ['item_id' => 2, 'item' => 'Kondisi Kaca Depan & Belakang', 'category' => 'Eksterior'],
                ['item_id' => 3, 'item' => 'Tekanan & Kondisi Ban',         'category' => 'Ban & Roda'],
                ['item_id' => 4, 'item' => 'Kondisi Velg',                  'category' => 'Ban & Roda'],
                ['item_id' => 5, 'item' => 'Rem Depan',                     'category' => 'Rem'],
                ['item_id' => 6, 'item' => 'Rem Belakang',                  'category' => 'Rem'],
                ['item_id' => 7, 'item' => 'Lampu Depan (Utama & Sein)',     'category' => 'Lampu'],
                ['item_id' => 8, 'item' => 'Lampu Belakang & Stop',         'category' => 'Lampu'],
                ['item_id' => 9, 'item' => 'Kondisi Mesin (Visual)',        'category' => 'Mesin'],
                ['item_id' => 10,'item' => 'Level & Kondisi Oli Mesin',     'category' => 'Mesin'],
            ];
            $statuses = [$kondisi1, $kondisi2, $kondisi3, 'baik', 'baik', 'baik', 'baik', 'cukup', 'baik', $kondisi3];
            return array_map(fn($p, $s) => array_merge($p, ['status' => $s, 'note' => '']), $poin, $statuses);
        };

        // ── Data 5 booking demo ────────────────────────────────────
        $demos = [
            // Booking 1: SELESAI — Avanza, kondisi cukup ada beberapa buruk
            [
                'code'        => 'SO-DEMO-0001',
                'customer'    => $customers[0],
                'vehicle'     => $vehicles[0],
                'inspector'   => $inspector1,
                'date'        => '2026-09-15',
                'time'        => '09:00:00',
                'status'      => 'completed',
                'checklist'   => $checklistLama('baik', 'cukup', 'buruk'),
                'summary'     => 'perlu_perhatian',
                'rekomendasi' => 'Segera ganti kampas rem depan dan periksa kondisi ban kiri belakang.',
                'price'       => 350000,
                'pkg_name'    => 'Inspeksi Standar',
            ],
            // Booking 2: SELESAI — Fortuner, kondisi baik semua
            [
                'code'        => 'SO-DEMO-0002',
                'customer'    => $customers[1],
                'vehicle'     => $vehicles[1],
                'inspector'   => $inspector2,
                'date'        => '2026-09-22',
                'time'        => '10:00:00',
                'status'      => 'completed',
                'checklist'   => $checklistLama('baik', 'baik', 'baik'),
                'summary'     => 'baik',
                'rekomendasi' => 'Kendaraan dalam kondisi prima. Lakukan servis rutin sesuai jadwal.',
                'price'       => 650000,
                'pkg_name'    => 'Inspeksi Premium',
            ],
            // Booking 3: SELESAI — Brio, ada yg cukup
            [
                'code'        => 'SO-DEMO-0003',
                'customer'    => $customers[2],
                'vehicle'     => $vehicles[2],
                'inspector'   => $inspector1,
                'date'        => '2026-09-29',
                'time'        => '13:00:00',
                'status'      => 'completed',
                'checklist'   => $checklistLama('baik', 'cukup', 'cukup'),
                'summary'     => 'cukup',
                'rekomendasi' => 'Periksa kondisi aki dan ganti filter udara dalam 1 bulan ke depan.',
                'price'       => 250000,
                'pkg_name'    => 'Inspeksi Basic',
            ],
            // Booking 4: PENDING — Avanza
            [
                'code'        => 'SO-DEMO-0004',
                'customer'    => $customers[0],
                'vehicle'     => $vehicles[0],
                'inspector'   => null,
                'date'        => '2026-10-10',
                'time'        => '09:00:00',
                'status'      => 'pending',
                'checklist'   => null,
                'summary'     => null,
                'rekomendasi' => null,
                'price'       => 350000,
                'pkg_name'    => 'Inspeksi Standar',
            ],
            // Booking 5: ON_PROGRESS — Fortuner
            [
                'code'        => 'SO-DEMO-0005',
                'customer'    => $customers[1],
                'vehicle'     => $vehicles[1],
                'inspector'   => $inspector2,
                'date'        => '2026-10-05',
                'time'        => '11:00:00',
                'status'      => 'on_progress',
                'checklist'   => null,
                'summary'     => null,
                'rekomendasi' => null,
                'price'       => 650000,
                'pkg_name'    => 'Inspeksi Premium',
            ],
        ];

        $created = 0;

        foreach ($demos as $demo) {
            // Idempotent: skip jika booking_code sudah ada
            if (Booking::where('booking_code', $demo['code'])->exists()) {
                continue;
            }

            // Buat booking
            $booking = Booking::create([
                'booking_code' => $demo['code'],
                'user_id'      => $demo['customer']->id,
                'vehicle_id'   => $demo['vehicle']->id,
                'package_id'   => $package->id,
                'inspector_id' => $demo['inspector']?->id,
                'booking_date' => $demo['date'],
                'booking_time' => $demo['time'],
                'status'       => $demo['status'],
                'notes'        => 'Data demo untuk pengujian.',
                'confirmed_at' => now(),
            ]);

            // Buat transaksi
            $transaction = Transaction::create([
                'transaction_code' => 'TRX-DEMO-' . str_pad($created + 1, 4, '0', STR_PAD_LEFT),
                'booking_id'       => $booking->id,
                'subtotal'         => 0,
                'tax'              => 0,
                'discount'         => 0,
                'total'            => 0,
                'payment_status'   => $demo['status'] === 'completed' ? 'paid' : 'unpaid',
            ]);

            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'tariff_id'      => null,
                'item_name'      => 'Biaya Inspeksi - ' . $demo['pkg_name'],
                'category'       => 'Inspeksi',
                'price'          => $demo['price'],
                'quantity'       => 1,
                'unit'           => 'paket',
                'subtotal'       => $demo['price'],
            ]);

            $transaction->recalculate();

            // Buat inspection result untuk booking selesai
            if ($demo['status'] === 'completed' && $demo['checklist']) {
                InspectionResult::create([
                    'booking_id'        => $booking->id,
                    'checklist_json'    => $demo['checklist'],
                    'condition_summary' => $demo['summary'],
                    'recommendation'    => $demo['rekomendasi'],
                    'inspector_notes'   => 'Catatan inspektor demo.',
                    'is_verified'       => true,
                    'verified_at'       => now(),
                    'completed_at'      => now(),
                ]);

                // Update booking status + payment
                $booking->update(['status' => 'completed']);
            }

            $created++;
        }

        $this->command->info("DemoBookingSeeder: {$created} booking demo berhasil dibuat.");
        $this->command->line('');
        $this->command->line('URL laporan untuk pengujian (login sebagai customer terlebih dahulu):');
        $this->command->line('  budi@demo.test    / Demo@2026  → Avanza  (SO-DEMO-0001)');
        $this->command->line('  siti@demo.test    / Demo@2026  → Fortuner (SO-DEMO-0002)');
        $this->command->line('  andi@demo.test    / Demo@2026  → Brio    (SO-DEMO-0003)');
        $this->command->line('');
        $this->command->line('Buka: /customer/laporan/{booking_id} setelah login sebagai customer yang bersangkutan.');
    }
}
