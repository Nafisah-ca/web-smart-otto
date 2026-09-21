<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $contents = [
            // ── General ──────────────────────────────────────────
            ['key' => 'site_name',      'label' => 'Nama Website',       'group' => 'general', 'type' => 'text',     'value' => 'Smart Otto'],
            ['key' => 'site_tagline',   'label' => 'Tagline',            'group' => 'general', 'type' => 'text',     'value' => 'Inspeksi Kendaraan Profesional & Terpercaya'],
            ['key' => 'site_phone',     'label' => 'Nomor Telepon',      'group' => 'general', 'type' => 'text',     'value' => ''],
            ['key' => 'site_email',     'label' => 'Email',              'group' => 'general', 'type' => 'text',     'value' => ''],
            ['key' => 'site_address',   'label' => 'Alamat',             'group' => 'general', 'type' => 'textarea', 'value' => ''],
            ['key' => 'site_maps_embed','label' => 'Embed Google Maps',  'group' => 'general', 'type' => 'text',     'value' => ''],
            // ── Hero ──────────────────────────────────────────────
            ['key' => 'hero_title',     'label' => 'Judul Hero',         'group' => 'hero',    'type' => 'text',     'value' => 'Inspeksi Kendaraan Anda dengan Teknisi Berpengalaman'],
            ['key' => 'hero_subtitle',  'label' => 'Subjudul Hero',      'group' => 'hero',    'type' => 'textarea', 'value' => 'Smart Otto hadir untuk memastikan kendaraan Anda dalam kondisi prima. Booking sekarang dan dapatkan laporan inspeksi digital lengkap.'],
            ['key' => 'hero_cta_text',  'label' => 'Teks Tombol CTA',   'group' => 'hero',    'type' => 'text',     'value' => 'Booking Inspeksi Sekarang'],
            // ── About ─────────────────────────────────────────────
            ['key' => 'about_title',    'label' => 'Judul Tentang Kami', 'group' => 'about',   'type' => 'text',     'value' => 'Tentang Smart Otto'],
            ['key' => 'about_content',  'label' => 'Konten Tentang',     'group' => 'about',   'type' => 'textarea', 'value' => 'Smart Otto adalah layanan inspeksi kendaraan profesional dengan teknisi bersertifikat dan peralatan diagnostik modern.'],
            ['key' => 'about_vision',   'label' => 'Visi',               'group' => 'about',   'type' => 'textarea', 'value' => ''],
            ['key' => 'about_mission',  'label' => 'Misi',               'group' => 'about',   'type' => 'textarea', 'value' => ''],
            // ── Stats ─────────────────────────────────────────────
            ['key' => 'stat_customers',   'label' => 'Jumlah Customer',    'group' => 'stats', 'type' => 'text', 'value' => ''],
            ['key' => 'stat_inspections', 'label' => 'Jumlah Inspeksi',    'group' => 'stats', 'type' => 'text', 'value' => ''],
            ['key' => 'stat_inspectors',  'label' => 'Jumlah Teknisi',     'group' => 'stats', 'type' => 'text', 'value' => ''],
            ['key' => 'stat_years',       'label' => 'Tahun Pengalaman',   'group' => 'stats', 'type' => 'text', 'value' => ''],
            // ── FAQ ───────────────────────────────────────────────
            ['key' => 'faq_items', 'label' => 'Item FAQ', 'group' => 'faq', 'type' => 'json', 'value' => json_encode([
                ['q' => 'Berapa lama proses inspeksi?',              'a' => 'Tergantung paket yang dipilih. Basic 60 menit, Standar 2 jam, Premium 3 jam.'],
                ['q' => 'Apakah bisa booking untuk hari yang sama?', 'a' => 'Bisa, selama slot masih tersedia.'],
                ['q' => 'Bagaimana cara melihat laporan inspeksi?',  'a' => 'Laporan tersedia di dashboard setelah inspeksi selesai dan diverifikasi inspektor.'],
                ['q' => 'Metode pembayaran apa yang diterima?',      'a' => 'Transfer Bank, QRIS, dan tunai di lokasi.'],
            ])],
            // ── Footer ────────────────────────────────────────────
            ['key' => 'footer_tagline',   'label' => 'Tagline Footer',   'group' => 'footer', 'type' => 'textarea', 'value' => 'Layanan inspeksi kendaraan profesional dengan teknisi berpengalaman.'],
            ['key' => 'footer_copyright', 'label' => 'Copyright',        'group' => 'footer', 'type' => 'text',     'value' => '© ' . date('Y') . ' Smart Otto. Seluruh hak cipta dilindungi.'],
            // ── Ops ───────────────────────────────────────────────
            ['key' => 'ops_weekday',  'label' => 'Jam Operasional Weekday', 'group' => 'ops', 'type' => 'text', 'value' => 'Senin - Jumat: 08.00 - 17.00 WIB'],
            ['key' => 'ops_saturday', 'label' => 'Jam Operasional Sabtu',   'group' => 'ops', 'type' => 'text', 'value' => 'Sabtu: 08.00 - 15.00 WIB'],
            ['key' => 'ops_sunday',   'label' => 'Jam Operasional Minggu',  'group' => 'ops', 'type' => 'text', 'value' => 'Minggu: Tutup'],
        ];

        $rows = [];
        foreach ($contents as $c) {
            $rows[] = array_merge($c, ['is_active' => true, 'created_at' => $now, 'updated_at' => $now]);
        }

        DB::table('cms_contents')->insert($rows);
    }
}
