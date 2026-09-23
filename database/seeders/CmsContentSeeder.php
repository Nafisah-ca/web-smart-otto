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
            ["key"=>"hero_title","label"=>"Judul Utama Hero","group"=>"hero","type"=>"text","value"=>"Inspeksi Kendaraan Anda dengan Teknisi Berpengalaman","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"hero_subtitle","label"=>"Subjudul Hero","group"=>"hero","type"=>"textarea","value"=>"Smart Otto hadir untuk memastikan kendaraan Anda dalam kondisi prima.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"hero_cta_text","label"=>"Teks Tombol","group"=>"hero","type"=>"text","value"=>"Booking Inspeksi Sekarang","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"hero_badge","label"=>"Badge Label","group"=>"hero","type"=>"text","value"=>"Teknisi Bersertifikat & Berpengalaman","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"hero_image","label"=>"Gambar Hero","group"=>"hero","type"=>"image","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_customers","label"=>"Customer Puas","group"=>"stats","type"=>"text","value"=>"500+","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_inspections","label"=>"Inspeksi Selesai","group"=>"stats","type"=>"text","value"=>"1.200+","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_inspectors","label"=>"Teknisi Aktif","group"=>"stats","type"=>"text","value"=>"15+","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_years","label"=>"Tahun Pengalaman","group"=>"stats","type"=>"text","value"=>"5+","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_icon_customers","label"=>"Icon Customer","group"=>"stats","type"=>"image","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_icon_inspections","label"=>"Icon Inspeksi","group"=>"stats","type"=>"image","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_icon_inspectors","label"=>"Icon Teknisi","group"=>"stats","type"=>"image","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"stat_icon_years","label"=>"Icon Pengalaman","group"=>"stats","type"=>"image","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"keunggulan_title","label"=>"Judul Keunggulan","group"=>"keunggulan","type"=>"text","value"=>"Mengapa Memilih Smart Otto?","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"keunggulan_subtitle","label"=>"Subjudul Keunggulan","group"=>"keunggulan","type"=>"textarea","value"=>"Kami menghadirkan standar inspeksi tertinggi.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cara_kerja_title","label"=>"Judul Cara Kerja","group"=>"cara_kerja","type"=>"text","value"=>"Cara Kerja Smart Otto","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cara_kerja_subtitle","label"=>"Subjudul Cara Kerja","group"=>"cara_kerja","type"=>"textarea","value"=>"Proses inspeksi yang mudah dalam 4 langkah.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cta_title","label"=>"Judul CTA","group"=>"cta","type"=>"text","value"=>"Siap Booking Inspeksi?","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cta_subtitle","label"=>"Subjudul CTA","group"=>"cta","type"=>"textarea","value"=>"Pastikan kendaraan Anda aman sebelum berkendara.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cta_button_text","label"=>"Teks Tombol CTA","group"=>"cta","type"=>"text","value"=>"Booking Sekarang","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_phone","label"=>"Telepon","group"=>"kontak","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_email","label"=>"Email","group"=>"kontak","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_address","label"=>"Alamat","group"=>"kontak","type"=>"textarea","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_maps_embed","label"=>"Maps Embed","group"=>"kontak","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"ops_weekday","label"=>"Jam Senin-Jumat","group"=>"kontak","type"=>"text","value"=>"Senin - Jumat: 08.00 - 17.00 WIB","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"ops_saturday","label"=>"Jam Sabtu","group"=>"kontak","type"=>"text","value"=>"Sabtu: 08.00 - 15.00 WIB","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"ops_sunday","label"=>"Jam Minggu","group"=>"kontak","type"=>"text","value"=>"Minggu: Tutup","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_tagline","label"=>"Tagline Footer","group"=>"footer","type"=>"textarea","value"=>"Layanan inspeksi kendaraan profesional.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_copyright","label"=>"Hak Cipta","group"=>"footer","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_social_ig","label"=>"Instagram","group"=>"footer","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_social_fb","label"=>"Facebook","group"=>"footer","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_social_wa","label"=>"WhatsApp","group"=>"footer","type"=>"text","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_name","label"=>"Nama Website","group"=>"general","type"=>"text","value"=>"Smart Otto","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"site_tagline","label"=>"Tagline Global","group"=>"general","type"=>"text","value"=>"Inspeksi Kendaraan Profesional & Terpercaya","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"about_title","label"=>"Judul Tentang","group"=>"general","type"=>"text","value"=>"Tentang Smart Otto","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"about_content","label"=>"Deskripsi","group"=>"general","type"=>"textarea","value"=>"Smart Otto adalah layanan inspeksi kendaraan profesional.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"about_vision","label"=>"Visi","group"=>"general","type"=>"textarea","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"about_mission","label"=>"Misi","group"=>"general","type"=>"textarea","value"=>"","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"keunggulan_items","label"=>"Daftar Keunggulan","group"=>"keunggulan","type"=>"json","value"=>"[{\"title\":\"Pemeriksaan 150+ Titik\",\"desc\":\"Setiap kendaraan diperiksa di lebih dari 150 titik.\",\"icon\":\"\"},{\"title\":\"Laporan Digital Real-Time\",\"desc\":\"Laporan dikirim ke smartphone Anda segera setelah selesai.\",\"icon\":\"\"},{\"title\":\"Teknisi Bersertifikat\",\"desc\":\"Tim teknisi tersertifikasi dan berpengalaman lebih dari 5 tahun.\",\"icon\":\"\"},{\"title\":\"Tepat Waktu\",\"desc\":\"Proses inspeksi selesai sesuai estimasi yang dijanjikan.\",\"icon\":\"\"},{\"title\":\"Jaminan Kualitas\",\"desc\":\"Hasil inspeksi dijamin akurat dengan metodologi berstandar internasional.\",\"icon\":\"\"},{\"title\":\"Pembayaran Fleksibel\",\"desc\":\"Transfer bank, QRIS, kartu debit\\/kredit, dan tunai.\",\"icon\":\"\"}]","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"cara_kerja_steps","label"=>"Langkah Cara Kerja","group"=>"cara_kerja","type"=>"json","value"=>"[{\"step\":\"1\",\"title\":\"Booking Online\",\"desc\":\"Pilih paket, isi data kendaraan, dan pilih jadwal.\",\"icon\":\"\"},{\"step\":\"2\",\"title\":\"Konfirmasi Admin\",\"desc\":\"Tim kami mengkonfirmasi booking dan menetapkan inspektor.\",\"icon\":\"\"},{\"step\":\"3\",\"title\":\"Proses Inspeksi\",\"desc\":\"Inspektor melakukan pemeriksaan menyeluruh sesuai paket.\",\"icon\":\"\"},{\"step\":\"4\",\"title\":\"Laporan Digital\",\"desc\":\"Terima laporan inspeksi lengkap di dashboard Anda.\",\"icon\":\"\"}]","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"faq_items","label"=>"FAQ","group"=>"general","type"=>"json","value"=>"[{\"q\":\"Berapa lama proses inspeksi?\",\"a\":\"Basic 60 menit, Standar 2 jam, Premium 3 jam.\"},{\"q\":\"Apakah bisa booking untuk hari yang sama?\",\"a\":\"Bisa, selama slot masih tersedia.\"},{\"q\":\"Bagaimana cara melihat laporan inspeksi?\",\"a\":\"Tersedia di dashboard setelah inspeksi selesai.\"},{\"q\":\"Metode pembayaran apa yang diterima?\",\"a\":\"Transfer Bank, QRIS, dan tunai di lokasi.\"}]","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
            ["key"=>"footer_copyright","label"=>"Hak Cipta","group"=>"footer","type"=>"text","value"=>"© 2026 Smart Otto. Seluruh hak cipta dilindungi.","is_active"=>true,"created_at"=>$now,"updated_at"=>$now],
        ];

        // Upsert: insert jika belum ada, update value jika sudah ada
        foreach ($rows as $row) {
            DB::table('cms_contents')->updateOrInsert(
                ['key' => $row['key']],
                $row
            );
        }
    }
}