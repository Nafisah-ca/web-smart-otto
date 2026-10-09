<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 * InspectionChecklistItemSeeder
 *
 * Strategi:
 * 1. Nonaktifkan semua poin LAMA (is_active = false) — tidak dihapus agar
 *    data inspeksi lama di checklist_json tetap valid.
 * 2. Insert 81 poin BARU (is_active = true) TANPA package_id (null).
 *    Semua paket mendapat checklist yang sama karena poin tidak lagi terikat paket.
 * 3. Idempotent: aman dijalankan berulang — poin baru dicek via item_name +
 *    package_id IS NULL + is_active sebelum diinsert.
 *
 * 81 poin sesuai PDF "contoh-laporan-smartotto-revisi", dikelompokkan ke 7 kategori:
 *   A. Interior Kendaraan   (poin 1–18)
 *   B. Scan Diagnostic      (poin 19–23)
 *   C. Ruang Mesin          (poin 24–39)
 *   D. Exterior Kendaraan   (poin 40–57, termasuk poin 48 di posisi benar)
 *   E. Kaki-kaki dan Ban    (poin 58–64)
 *   F. Test Drive           (poin 65–74)
 *   G. Sejarah dan Surat    (poin 75–81)
 */
class InspectionChecklistItemSeeder extends Seeder
{
    public function run(): void
    {
        // ── 1. Nonaktifkan semua poin lama ────────────────────────
        DB::table('inspection_checklist_item')
            ->where('is_active', true)
            ->update(['is_active' => false]);

        $this->command->info('Poin lama dinonaktifkan (is_active = false).');

        // ── 2. Definisi 81 poin baru ──────────────────────────────
        $poin = $this->getChecklistItems();

        // ── 4. Insert poin baru per paket (idempotent) ────────────
        // Poin baru TIDAK terikat paket (package_id = null).
        // Diinsert sekali saja, bukan per paket.
        $now      = now();
        $inserted = 0;

        foreach ($poin as $item) {
            $exists = DB::table('inspection_checklist_item')
                ->whereNull('package_id')
                ->where('item_name', $item['item_name'])
                ->where('is_active', true)
                ->exists();

            if (! $exists) {
                DB::table('inspection_checklist_item')->insert([
                    'package_id'  => null,       // tidak terikat paket
                    'item_name'   => $item['item_name'],
                    'category'    => $item['category'],
                    'description' => null,
                    'sort_order'  => $item['sort_order'],
                    'is_active'   => true,
                    'created_at'  => $now,
                    'updated_at'  => $now,
                ]);
                $inserted++;
            }
        }

        $this->command->info("Seeder selesai: {$inserted} poin baru diinsert (tanpa relasi paket).");
    }

    // ─────────────────────────────────────────────────────────────
    // 81 poin checklist sesuai PDF revisi
    // ─────────────────────────────────────────────────────────────
    private function getChecklistItems(): array
    {
        return [
            // ── A. Interior Kendaraan ──────────────────────────────
            ['sort_order' =>  1, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi remote entry system'],
            ['sort_order' =>  2, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Sistem penguncian pintu'],
            ['sort_order' =>  3, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Cek performa Air Conditioning System'],
            ['sort_order' =>  4, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi Radio/Bluetooth speaker'],
            ['sort_order' =>  5, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi setir & tombol kemudi'],
            ['sort_order' =>  6, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi rem parkir'],
            ['sort_order' =>  7, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi pedal gas, rem, kopling'],
            ['sort_order' =>  8, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Indikator panel instrumen'],
            ['sort_order' =>  9, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi klakson'],
            ['sort_order' => 10, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Penyemprotan air wiper'],
            ['sort_order' => 11, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi jok kursi'],
            ['sort_order' => 12, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi penyetelan kursi'],
            ['sort_order' => 13, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi & fungsi sabuk pengaman'],
            ['sort_order' => 14, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi trim & panel pintu'],
            ['sort_order' => 15, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi karpet dasar & lantai'],
            ['sort_order' => 16, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Fungsi tombol pembuka kap depan'],
            ['sort_order' => 17, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi karpet ruang bagasi'],
            ['sort_order' => 18, 'category' => 'A. Interior Kendaraan', 'item_name' => 'Kondisi dongkrak & tool kit'],

            // ── B. Scan Diagnostic ─────────────────────────────────
            ['sort_order' => 19, 'category' => 'B. Scan Diagnostic', 'item_name' => 'Engine (scan tool)'],
            ['sort_order' => 20, 'category' => 'B. Scan Diagnostic', 'item_name' => 'Transmission (scan tool)'],
            ['sort_order' => 21, 'category' => 'B. Scan Diagnostic', 'item_name' => 'ABS (scan tool)'],
            ['sort_order' => 22, 'category' => 'B. Scan Diagnostic', 'item_name' => 'Airbag (scan tool)'],
            ['sort_order' => 23, 'category' => 'B. Scan Diagnostic', 'item_name' => 'BCM (scan tool)'],

            // ── C. Ruang Mesin ─────────────────────────────────────
            ['sort_order' => 24, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi oli mesin'],
            ['sort_order' => 25, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi radiator coolant'],
            ['sort_order' => 26, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi cairan rem'],
            ['sort_order' => 27, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi oli power steering'],
            ['sort_order' => 28, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi cairan wiper'],
            ['sort_order' => 29, 'category' => 'C. Ruang Mesin', 'item_name' => 'Tidak terdapat kebocoran oli mesin'],
            ['sort_order' => 30, 'category' => 'C. Ruang Mesin', 'item_name' => 'Tidak ada kebocoran oli transmisi'],
            ['sort_order' => 31, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi selang-selang & klem'],
            ['sort_order' => 32, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi tali kipas'],
            ['sort_order' => 33, 'category' => 'C. Ruang Mesin', 'item_name' => 'Fungsi dinamo starter'],
            ['sort_order' => 34, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi aki'],
            ['sort_order' => 35, 'category' => 'C. Ruang Mesin', 'item_name' => 'Fungsi alternator / pengisian'],
            ['sort_order' => 36, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi engine mounting (visual)'],
            ['sort_order' => 37, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi master rem (visual)'],
            ['sort_order' => 38, 'category' => 'C. Ruang Mesin', 'item_name' => 'Fungsi compressor AC'],
            ['sort_order' => 39, 'category' => 'C. Ruang Mesin', 'item_name' => 'Kondisi radiator'],

            // ── D. Exterior Kendaraan ──────────────────────────────
            // Catatan: poin 48 "Kondisi kap depan" ditempatkan setelah poin 47
            // (bukan setelah 56-57 seperti posisi di PDF yang tercampur)
            ['sort_order' => 40, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Tidak ada indikasi bekas banjir'],
            ['sort_order' => 41, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Tidak ada indikasi bekas kecelakaan besar'],
            ['sort_order' => 42, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Inspeksi panel body'],
            ['sort_order' => 43, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Inspeksi bumper depan'],
            ['sort_order' => 44, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Inspeksi bumper belakang'],
            ['sort_order' => 45, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi grille depan'],
            ['sort_order' => 46, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi trim (list mobil)'],
            ['sort_order' => 47, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi pintu'],
            ['sort_order' => 48, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi kap depan'],   // ← dipindah ke posisi benar
            ['sort_order' => 49, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi atap'],
            ['sort_order' => 50, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi kaca depan/samping/belakang'],
            ['sort_order' => 51, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Fungsi wiper depan & belakang'],
            ['sort_order' => 52, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi spion'],
            ['sort_order' => 53, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi & fungsi lampu headlight'],
            ['sort_order' => 54, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi & fungsi lampu belakang/stop'],
            ['sort_order' => 55, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi & fungsi lampu sein'],
            ['sort_order' => 56, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Fungsi lampu hazard'],
            ['sort_order' => 57, 'category' => 'D. Exterior Kendaraan', 'item_name' => 'Kondisi lampu plat nomor'],

            // ── E. Kaki-kaki dan Ban ───────────────────────────────
            ['sort_order' => 58, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Kondisi Rack & Pinion boots'],
            ['sort_order' => 59, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Kondisi CV boots'],
            ['sort_order' => 60, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Kondisi Swing Arm bushing'],
            ['sort_order' => 61, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Kondisi per & shock absorber'],
            ['sort_order' => 62, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Brake pad depan (rem cakram)'],
            ['sort_order' => 63, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Brake pad belakang'],
            ['sort_order' => 64, 'category' => 'E. Kaki-kaki dan Ban', 'item_name' => 'Kondisi piringan cakram depan & belakang'],

            // ── F. Test Drive ──────────────────────────────────────
            ['sort_order' => 65, 'category' => 'F. Test Drive', 'item_name' => 'Mesin menyala dengan baik'],
            ['sort_order' => 66, 'category' => 'F. Test Drive', 'item_name' => 'Mesin idle dengan baik'],
            ['sort_order' => 67, 'category' => 'F. Test Drive', 'item_name' => 'Akselerasi mesin saat berjalan'],
            ['sort_order' => 68, 'category' => 'F. Test Drive', 'item_name' => 'Suara mesin normal'],
            ['sort_order' => 69, 'category' => 'F. Test Drive', 'item_name' => 'Perpindahan gigi transmisi normal'],
            ['sort_order' => 70, 'category' => 'F. Test Drive', 'item_name' => 'Kopling berfungsi normal'],
            ['sort_order' => 71, 'category' => 'F. Test Drive', 'item_name' => 'Steer normal'],
            ['sort_order' => 72, 'category' => 'F. Test Drive', 'item_name' => 'Suara suspensi normal'],
            ['sort_order' => 73, 'category' => 'F. Test Drive', 'item_name' => 'Performa rem normal'],
            ['sort_order' => 74, 'category' => 'F. Test Drive', 'item_name' => 'Gauge dashboard berfungsi normal'],

            // ── G. Sejarah dan Surat ───────────────────────────────
            ['sort_order' => 75, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'Riwayat perawatan berkala'],
            ['sort_order' => 76, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'Stiker uji emisi'],
            ['sort_order' => 77, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'Kunci serep tersedia'],
            ['sort_order' => 78, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'Kunci remote control tersedia'],
            ['sort_order' => 79, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'No. rangka sesuai dokumen'],
            ['sort_order' => 80, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'No. mesin sesuai dokumen'],
            ['sort_order' => 81, 'category' => 'G. Sejarah dan Surat', 'item_name' => 'Buku manual & servis tersedia'],
        ];
    }
}
