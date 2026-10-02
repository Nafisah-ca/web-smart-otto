<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class KenapaHarusKamiSeeder extends Seeder
{
    public function run(): void
    {
        $now   = now();
        $group = 'kenapa_harus_kami';

        $rows = [
            // ── Teks section ─────────────────────────────────────────────
            [
                'key'   => 'kenapa_judul',
                'label' => 'Judul Section',
                'type'  => 'text',
                'value' => 'Kenapa Harus Kami?',
            ],
            [
                'key'   => 'kenapa_subjudul',
                'label' => 'Subjudul Section',
                'type'  => 'text',
                'value' => 'Standar inspeksi tinggi agar Anda mendapat informasi kendaraan yang akurat dan terpercaya.',
            ],

            // ── Kartu 1 — Alat Canggih ────────────────────────────────────
            [
                'key'   => 'kenapa_card1_icon',
                'label' => 'Ikon Kartu 1',
                'type'  => 'text',
                'value' => 'monitor',
            ],
            [
                'key'   => 'kenapa_card1_judul',
                'label' => 'Judul Kartu 1',
                'type'  => 'text',
                'value' => 'Alat Canggih',
            ],
            [
                'key'   => 'kenapa_card1_desc',
                'label' => 'Deskripsi Kartu 1',
                'type'  => 'textarea',
                'value' => 'Peralatan berkualitas yang membuat inspektor kami memiliki akurasi tinggi dalam pengecekan.',
            ],

            // ── Kartu 2 — Laporan Online ──────────────────────────────────
            [
                'key'   => 'kenapa_card2_icon',
                'label' => 'Ikon Kartu 2',
                'type'  => 'text',
                'value' => 'file-text',
            ],
            [
                'key'   => 'kenapa_card2_judul',
                'label' => 'Judul Kartu 2',
                'type'  => 'text',
                'value' => 'Laporan Online',
            ],
            [
                'key'   => 'kenapa_card2_desc',
                'label' => 'Deskripsi Kartu 2',
                'type'  => 'textarea',
                'value' => 'Kondisi kendaraan bisa diketahui dari laporan inspeksi online dengan detail dan lengkap.',
            ],
            [
                'key'   => 'kenapa_card2_pdf',
                'label' => 'File Contoh Laporan (PDF)',
                'type'  => 'file',
                // path relatif terhadap Storage::disk('public')
                'value' => 'cms/contoh-laporan-smartotto.pdf',
            ],

            // ── Kartu 3 — Profesional ─────────────────────────────────────
            [
                'key'   => 'kenapa_card3_icon',
                'label' => 'Ikon Kartu 3',
                'type'  => 'text',
                'value' => 'shield-check',
            ],
            [
                'key'   => 'kenapa_card3_judul',
                'label' => 'Judul Kartu 3',
                'type'  => 'text',
                'value' => 'Profesional',
            ],
            [
                'key'   => 'kenapa_card3_desc',
                'label' => 'Deskripsi Kartu 3',
                'type'  => 'textarea',
                'value' => 'Tim inspektor kami ahli dan berpengalaman dalam inspeksi kendaraan secara teliti.',
            ],

            // ── Kartu 4 — Transparan ──────────────────────────────────────
            [
                'key'   => 'kenapa_card4_icon',
                'label' => 'Ikon Kartu 4',
                'type'  => 'text',
                'value' => 'eye',
            ],
            [
                'key'   => 'kenapa_card4_judul',
                'label' => 'Judul Kartu 4',
                'type'  => 'text',
                'value' => 'Transparan',
            ],
            [
                'key'   => 'kenapa_card4_desc',
                'label' => 'Deskripsi Kartu 4',
                'type'  => 'textarea',
                'value' => 'Semua status inspeksi & tagihan bisa dipantau customer langsung dari akun mereka.',
            ],
        ];

        foreach ($rows as $row) {
            $existing = DB::table('cms_content')->where('key', $row['key'])->first();

            if (!$existing) {
                DB::table('cms_content')->insert([
                    'key'        => $row['key'],
                    'label'      => $row['label'],
                    'group'      => $group,
                    'type'       => $row['type'],
                    'value'      => $row['value'],
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);
            } else {
                // type=file: TIDAK timpa value supaya upload admin sebelumnya tidak hilang
                if ($existing->type === 'file') {
                    DB::table('cms_content')->where('key', $row['key'])->update([
                        'label'      => $row['label'],
                        'group'      => $group,
                        'updated_at' => $now,
                    ]);
                } else {
                    DB::table('cms_content')->where('key', $row['key'])->update([
                        'label'      => $row['label'],
                        'group'      => $group,
                        'type'       => $row['type'],
                        'value'      => $row['value'],
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
