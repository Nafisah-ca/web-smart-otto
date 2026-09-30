<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CmsContentSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $rows = [
            // HERO — image dikosongkan hanya saat insert pertama, tidak overwrite kalau sudah ada
            ["key"=>"hero_title",    "label"=>"Judul Utama Hero",       "group"=>"hero","type"=>"text",    "value"=>"Inspeksi Kendaraan Anda dengan Teknisi Berpengalaman"],
            ["key"=>"hero_subtitle", "label"=>"Subjudul Hero",          "group"=>"hero","type"=>"textarea","value"=>"Smart Otto hadir untuk memastikan kendaraan Anda dalam kondisi prima."],
            ["key"=>"hero_cta_text", "label"=>"Teks Tombol",            "group"=>"hero","type"=>"text",    "value"=>"Booking Inspeksi Sekarang"],
            ["key"=>"hero_badge",    "label"=>"Badge Label",            "group"=>"hero","type"=>"text",    "value"=>"Teknisi Bersertifikat & Berpengalaman"],

            // STATISTIK
            ["key"=>"stat_customers",   "label"=>"Customer Puas",    "group"=>"stats","type"=>"text","value"=>"500+"],
            ["key"=>"stat_inspections", "label"=>"Inspeksi Selesai", "group"=>"stats","type"=>"text","value"=>"1.200+"],
            ["key"=>"stat_inspectors",  "label"=>"Teknisi Aktif",    "group"=>"stats","type"=>"text","value"=>"15+"],
            ["key"=>"stat_years",       "label"=>"Tahun Pengalaman", "group"=>"stats","type"=>"text","value"=>"5+"],

            // KATEGORI
            ["key"=>"kategori_title","label"=>"Judul Kategori","group"=>"kategori","type"=>"text","value"=>"Pilih Kategori"],
            ["key"=>"kategori_items","label"=>"Daftar Kategori","group"=>"kategori","type"=>"json","value"=>'[{"name":"Kat. 1","harga":"Rp 500.000 - 800.000,-","img":"","models":["Alphard","Land Cruiser","Palisade","Mazda CX-8","Sedan Mercy","Sedan BMW","Mini Cooper","Hilux","Triton","Range Rover","All Lexus","Haice","Mazda CX-60","Chevrolet Captiva","SUV Mercy","SUV BMW","Rubicon","Navara","Ford Ranger","City Car Eropa \/ USA"]},{"name":"Kat. 2","harga":"Rp 350.000 - 500.000,-","img":"","models":["Fortuner","Pajero","Everest","Rush","Terios","Innova","Kijang","Avanza","Xenia","CX-5","HR-V","CR-V","X-Trail","Outlander","Ertiga","XL7","Livina","Freed","Jazz","Yaris"]},{"name":"Kat. 3","harga":"Rp 250.000 - 350.000,-","img":"","models":["Brio","Agya","Ayla","Calya","Sigra","March","Mirage","Picanto","Ignis","S-Cross","Swift","Baleno","Karimun","Wagon R","Splash","Alto","Celerio","Sirion","Veloz","Xpander"]}]'],

            // KEUNGGULAN
            ["key"=>"keunggulan_title",    "label"=>"Judul Keunggulan",    "group"=>"keunggulan","type"=>"text",    "value"=>"Mengapa Memilih Smart Otto?"],
            ["key"=>"keunggulan_subtitle", "label"=>"Subjudul Keunggulan", "group"=>"keunggulan","type"=>"textarea","value"=>"Kami menghadirkan standar inspeksi tertinggi."],
            ["key"=>"keunggulan_items",    "label"=>"Daftar Keunggulan",   "group"=>"keunggulan","type"=>"json",
             "value"=>'[{"title":"Pemeriksaan 150+ Titik","desc":"Setiap kendaraan diperiksa di lebih dari 150 titik.","icon":""},{"title":"Laporan Digital Real-Time","desc":"Laporan dikirim ke smartphone Anda segera setelah selesai.","icon":""},{"title":"Teknisi Bersertifikat","desc":"Tim teknisi tersertifikasi dan berpengalaman lebih dari 5 tahun.","icon":""},{"title":"Tepat Waktu","desc":"Proses inspeksi selesai sesuai estimasi yang dijanjikan.","icon":""},{"title":"Jaminan Kualitas","desc":"Hasil inspeksi dijamin akurat dengan metodologi berstandar internasional.","icon":""},{"title":"Pembayaran Fleksibel","desc":"Transfer bank, QRIS, kartu debit\/kredit, dan tunai.","icon":""}]'],

            // CARA KERJA
            ["key"=>"cara_kerja_title",    "label"=>"Judul Cara Kerja",    "group"=>"cara_kerja","type"=>"text",    "value"=>"Cara Kerja Smart Otto"],
            ["key"=>"cara_kerja_subtitle", "label"=>"Subjudul Cara Kerja", "group"=>"cara_kerja","type"=>"textarea","value"=>"Proses inspeksi yang mudah dalam 4 langkah."],
            ["key"=>"cara_kerja_steps",    "label"=>"Langkah Cara Kerja",  "group"=>"cara_kerja","type"=>"json",
             "value"=>'[{"step":"1","title":"Booking Online","desc":"Pilih paket, isi data kendaraan, dan pilih jadwal.","icon":""},{"step":"2","title":"Konfirmasi Admin","desc":"Tim kami mengkonfirmasi booking dan menetapkan inspektor.","icon":""},{"step":"3","title":"Proses Inspeksi","desc":"Inspektor melakukan pemeriksaan menyeluruh sesuai paket.","icon":""},{"step":"4","title":"Laporan Digital","desc":"Terima laporan inspeksi lengkap di dashboard Anda.","icon":""}]'],

            // CTA
            ["key"=>"cta_title",       "label"=>"Judul CTA",      "group"=>"cta","type"=>"text",    "value"=>"Siap Booking Inspeksi?"],
            ["key"=>"cta_subtitle",    "label"=>"Subjudul CTA",   "group"=>"cta","type"=>"textarea","value"=>"Pastikan kendaraan Anda aman sebelum berkendara."],
            ["key"=>"cta_button_text", "label"=>"Teks Tombol CTA","group"=>"cta","type"=>"text",    "value"=>"Booking Sekarang"],

            // KONTAK
            ["key"=>"site_phone",      "label"=>"Telepon",            "group"=>"kontak","type"=>"text",    "value"=>""],
            ["key"=>"site_email",      "label"=>"Email",              "group"=>"kontak","type"=>"text",    "value"=>""],
            ["key"=>"site_address",    "label"=>"Alamat",             "group"=>"kontak","type"=>"textarea","value"=>""],
            ["key"=>"site_maps_embed", "label"=>"Maps Embed",         "group"=>"kontak","type"=>"text",    "value"=>""],
            ["key"=>"ops_weekday",     "label"=>"Jam Senin-Jumat",    "group"=>"kontak","type"=>"text",    "value"=>"Senin - Jumat: 08.00 - 17.00 WIB"],
            ["key"=>"ops_saturday",    "label"=>"Jam Sabtu",          "group"=>"kontak","type"=>"text",    "value"=>"Sabtu: 08.00 - 15.00 WIB"],
            ["key"=>"ops_sunday",      "label"=>"Jam Minggu",         "group"=>"kontak","type"=>"text",    "value"=>"Minggu: Tutup"],

            // FOOTER
            ["key"=>"footer_tagline",   "label"=>"Tagline Footer","group"=>"footer","type"=>"textarea","value"=>"Layanan inspeksi kendaraan profesional."],
            ["key"=>"footer_copyright", "label"=>"Hak Cipta",     "group"=>"footer","type"=>"text",   "value"=>"© ".date('Y')." Smart Otto. Seluruh hak cipta dilindungi."],
            ["key"=>"footer_social_ig", "label"=>"Instagram",     "group"=>"footer","type"=>"text",   "value"=>""],
            ["key"=>"footer_social_fb", "label"=>"Facebook",      "group"=>"footer","type"=>"text",   "value"=>""],
            ["key"=>"footer_social_wa", "label"=>"WhatsApp",      "group"=>"footer","type"=>"text",   "value"=>""],

            // GENERAL
            ["key"=>"site_name",       "label"=>"Nama Website",          "group"=>"general","type"=>"text",    "value"=>"Smart Otto"],
            ["key"=>"site_tagline",    "label"=>"Tagline Global",        "group"=>"general","type"=>"text",    "value"=>"Inspeksi Kendaraan Profesional & Terpercaya"],
            ["key"=>"about_title",     "label"=>"Judul Tentang",         "group"=>"general","type"=>"text",    "value"=>"Tentang Smart Otto"],
            ["key"=>"about_content",   "label"=>"Deskripsi",             "group"=>"general","type"=>"textarea","value"=>"Smart Otto adalah layanan inspeksi kendaraan profesional dengan teknisi bersertifikat dan peralatan diagnostik modern."],
            ["key"=>"about_welcome",   "label"=>"Sambutan",              "group"=>"general","type"=>"textarea","value"=>"Selamat datang di Smart Otto! Kami hadir sebagai solusi inspeksi kendaraan profesional yang dapat Anda percaya."],
            ["key"=>"about_vision",    "label"=>"Visi",                  "group"=>"general","type"=>"textarea","value"=>"Menjadi layanan inspeksi kendaraan terpercaya dan terdepan di Indonesia."],
            ["key"=>"about_mission",   "label"=>"Misi",                  "group"=>"general","type"=>"textarea","value"=>"Memberikan inspeksi kendaraan yang akurat dan transparan.\nMenghadirkan teknisi bersertifikat dan berpengalaman.\nMemberikan laporan digital yang mudah dipahami.\nMenjaga kepercayaan pelanggan dengan standar kualitas tinggi."],
            ["key"=>"about_logo",      "label"=>"Logo Perusahaan",       "group"=>"general","type"=>"image",   "value"=>""],
            ["key"=>"faq_items",       "label"=>"FAQ",                   "group"=>"general","type"=>"json",
             "value"=>'[{"q":"Berapa lama proses inspeksi?","a":"Basic 60 menit, Standar 2 jam, Premium 3 jam."},{"q":"Apakah bisa booking untuk hari yang sama?","a":"Bisa, selama slot masih tersedia."},{"q":"Bagaimana cara melihat laporan inspeksi?","a":"Tersedia di dashboard setelah inspeksi selesai."},{"q":"Metode pembayaran apa yang diterima?","a":"Transfer Bank, QRIS, dan tunai di lokasi."}]'],
        ];

        foreach ($rows as $row) {
            $existing = DB::table('cms_contents')->where('key', $row['key'])->first();

            if (!$existing) {
                // Belum ada — insert dengan semua field
                DB::table('cms_contents')->insert(array_merge($row, [
                    'is_active'  => true,
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            } else {
                // Sudah ada — update hanya field NON-image
                // Field image (type=image) TIDAK ditimpa supaya upload admin tidak hilang
                if ($existing->type !== 'image') {
                    DB::table('cms_contents')->where('key', $row['key'])->update([
                        'label'      => $row['label'],
                        'value'      => $row['value'],
                        'updated_at' => $now,
                    ]);
                }
                // Kalau image: hanya update label saja, value dibiarkan
                else {
                    DB::table('cms_contents')->where('key', $row['key'])->update([
                        'label'      => $row['label'],
                        'updated_at' => $now,
                    ]);
                }
            }
        }
    }
}
