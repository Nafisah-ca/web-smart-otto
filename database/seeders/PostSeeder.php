<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Idempotent: safe to run multiple times.
     * Uses updateOrCreate keyed on slug — no duplicates.
     */
    public function run(): void
    {
        $posts = $this->posts();

        foreach ($posts as $data) {
            Post::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }

        $this->command->info('PostSeeder: ' . count($posts) . ' artikel blog berhasil di-seed.');
    }

    // ─────────────────────────────────────────────────────────────
    // Data artikel
    // ─────────────────────────────────────────────────────────────

    private function posts(): array
    {
        return [

            // ── Artikel 1 ─────────────────────────────────────────
            [
                'title'        => 'Apa Itu Inspeksi Mobil Bekas Independen dan Mengapa Anda Membutuhkannya?',
                'slug'         => 'apa-itu-inspeksi-mobil-bekas-independen',
                'category'     => 'Panduan Inspeksi',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-09-01 08:00:00',
                'status'       => 'published',
                'excerpt'      => 'Banyak pembeli mobil bekas tergiur harga murah tanpa menyadari risiko tersembunyi di baliknya. Inspeksi independen hadir sebagai solusi untuk memastikan kondisi nyata kendaraan sebelum uang berpindah tangan.',
                'meta_title'   => 'Apa Itu Inspeksi Mobil Bekas Independen? — Smart Otto',
                'meta_description' => 'Pelajari definisi inspeksi mobil bekas independen, cara kerjanya, dan alasan kuat mengapa setiap calon pembeli mobil bekas wajib melakukannya.',
                'thumbnail_url'=> 'seed/blog/inspeksi-mobil-bekas.svg',
                'cta_enabled'  => true,
                'cta_title'    => 'Siap Inspeksi Kendaraan Incaran Anda?',
                'cta_subtitle' => 'Dapatkan laporan lengkap dari inspektor bersertifikat Smart Otto.',
                'cta_button_text' => 'Booking Inspeksi Sekarang',
                'cta_button_url'  => '/booking',
                'content'      => <<<HTML
<h2>Apa Itu Inspeksi Mobil Bekas Independen?</h2>
<p>Inspeksi mobil bekas independen adalah proses pemeriksaan menyeluruh terhadap kondisi fisik, mekanis, dan administratif sebuah kendaraan bekas yang dilakukan oleh pihak ketiga yang <strong>tidak memiliki kepentingan</strong> dalam transaksi jual-beli tersebut. Berbeda dengan pemeriksaan yang dilakukan oleh penjual atau dealer, inspektor independen bekerja murni untuk kepentingan calon pembeli.</p>
<p>Hasilnya berupa laporan detail yang mencakup kondisi eksterior, interior, mesin, kaki-kaki, sistem kelistrikan, hingga kelengkapan dokumen. Laporan ini menjadi dasar negosiasi yang kuat dan pelindung dari potensi kerugian finansial.</p>

<h2>Kenapa Inspeksi Independen Berbeda dari Pemeriksaan Biasa?</h2>
<p>Ketika Anda membeli mobil bekas, penjual tentu ingin kendaraannya terlihat sebaik mungkin. Cat dipoles, interior dibersihkan, bahkan beberapa cacat disembunyikan dengan produk kimia. Inspektur independen dilatih khusus untuk menemukan hal-hal yang tidak terlihat oleh mata awam.</p>

<h3>Tidak Bergantung pada Penjual</h3>
<p>Inspektor independen tidak mendapat komisi dari penjual. Mereka dibayar oleh calon pembeli, sehingga loyalitasnya sepenuhnya kepada Anda sebagai klien.</p>

<h3>Menggunakan Alat Profesional</h3>
<p>OBD scanner untuk membaca kode error mesin, paint depth gauge untuk mendeteksi bekas perbaikan bodi, serta lift untuk memeriksa bagian bawah kendaraan — alat-alat ini tidak tersedia di showroom biasa.</p>

<h3>Laporan Tertulis dan Terstruktur</h3>
<p>Anda menerima laporan dengan foto dan penilaian per komponen, bukan hanya pendapat lisan yang mudah dilupakan atau disangkal.</p>

<h2>Siapa yang Harus Menggunakan Layanan Ini?</h2>
<p>Singkatnya: <strong>siapa pun yang berencana membeli mobil bekas</strong>. Namun inspeksi independen terutama sangat berguna bagi:</p>
<ul>
  <li>Pembeli yang tidak memiliki latar belakang otomotif</li>
  <li>Pembelian dari penjual perorangan (bukan dealer resmi)</li>
  <li>Kendaraan berumur lebih dari 3 tahun</li>
  <li>Transaksi jarak jauh di mana Anda tidak bisa hadir langsung</li>
  <li>Kendaraan dengan harga di atas rata-rata pasar (perlu verifikasi kondisi)</li>
</ul>

<h2>Berapa Biaya Inspeksi Dibanding Kerugian Potensial?</h2>
<p>Biaya inspeksi independen profesional berkisar antara Rp 300.000 hingga Rp 700.000 tergantung jenis kendaraan dan kedalaman pemeriksaan. Bandingkan dengan potensi biaya perbaikan transmisi (Rp 5–20 juta), perbaikan rangka (Rp 10–50 juta), atau ganti mesin (Rp 30–80 juta) yang bisa menanti tanpa inspeksi.</p>
<p>Dengan perspektif ini, biaya inspeksi adalah <em>investasi</em>, bukan pengeluaran.</p>

<h2>Kesimpulan</h2>
<p>Inspeksi mobil bekas independen memberikan Anda informasi yang jujur dan terverifikasi sebelum membuat keputusan pembelian besar. Di Smart Otto, tim inspektor kami memiliki pengalaman lebih dari lima tahun dan menggunakan 150+ poin pemeriksaan untuk setiap kendaraan. Hubungi kami sebelum tanda tangan di atas kontrak.</p>
HTML,
            ],

            // ── Artikel 2 ─────────────────────────────────────────
            [
                'title'        => 'Apa Saja yang Diperiksa Saat Inspeksi Mobil Bekas? Panduan 150 Poin',
                'slug'         => 'apa-saja-yang-diperiksa-inspeksi-mobil-bekas',
                'category'     => 'Panduan Inspeksi',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-09-08 09:00:00',
                'status'       => 'published',
                'excerpt'      => 'Dari kondisi cat hingga kode error tersembunyi di ECU, inspeksi mobil bekas mencakup ratusan poin pemeriksaan. Kenali apa saja yang dievaluasi agar Anda tahu nilai laporan yang Anda terima.',
                'meta_title'   => '150 Poin Pemeriksaan Inspeksi Mobil Bekas — Smart Otto',
                'meta_description' => 'Panduan lengkap 150 poin pemeriksaan inspeksi mobil bekas: mesin, transmisi, kaki-kaki, bodi, interior, kelistrikan, dan dokumen.',
                'thumbnail_url'=> 'seed/blog/mesin-mobil-bekas.svg',
                'cta_enabled'  => true,
                'cta_title'    => 'Pesan Inspeksi Lengkap 150 Poin',
                'cta_subtitle' => 'Inspektor bersertifikat hadir ke lokasi Anda.',
                'cta_button_text' => 'Lihat Paket Inspeksi',
                'cta_button_url'  => '/layanan',
                'content'      => <<<HTML
<h2>Mengapa Jumlah Poin Pemeriksaan Itu Penting?</h2>
<p>Inspeksi yang baik bukan sekadar "lihat-lihat" kendaraan. Setiap komponen memiliki cara pemeriksaan spesifik dan standar kondisi yang dapat diterima. Semakin banyak poin yang diperiksa, semakin kecil kemungkinan ada masalah tersembunyi yang lolos.</p>

<h2>Mesin dan Ruang Mesin</h2>
<h3>Kondisi Visual Mesin</h3>
<p>Inspektor memeriksa kebocoran oli, coolant, dan power steering fluid. Selang, kabel, dan konektor juga dicek keutuhannya. Bekas overhaul atau penggantian gasket kepala bisa terdeteksi dari pola kerak di sekitar blok mesin.</p>

<h3>Pemindaian ECU dengan OBD Scanner</h3>
<p>Scanner dihubungkan ke port OBD-II untuk membaca Diagnostic Trouble Codes (DTC). Kode yang sudah dihapus penjual pun bisa terdeteksi melalui pola freeze frame data dan readiness monitor yang belum complete.</p>

<h3>Kompresi Mesin</h3>
<p>Untuk kendaraan di atas 5 tahun, tes kompresi per silinder memberi gambaran kesehatan ring piston dan klep. Perbedaan tekanan antar silinder lebih dari 10% adalah tanda peringatan.</p>

<h2>Transmisi</h2>
<h3>Transmisi Manual</h3>
<p>Kopling diperiksa untuk tanda-tanda selip, getaran, atau bunyi kasar. Perpindahan gigi diuji di semua posisi termasuk gigi mundur.</p>

<h3>Transmisi Otomatis</h3>
<p>Karakteristik perpindahan gigi, respons kickdown, dan kondisi ATF (warna dan bau) menjadi indikator utama. Transmisi yang kasar atau tertunda perpindahannya bisa mengindikasikan kebutuhan overhaul mahal.</p>

<h2>Kaki-Kaki dan Kemudi</h2>
<h3>Suspensi Depan dan Belakang</h3>
<p>Shock absorber, ball joint, tie rod end, dan bushing diperiksa secara visual dan dengan uji guncangan (bounce test). Bunyi kletek saat melewati jalan tidak rata adalah gejala ball joint atau bushing aus.</p>

<h3>Sistem Rem</h3>
<p>Ketebalan kampas rem, kondisi cakram/tromol, serta fungsi rem parkir dievaluasi. Kebocoran pada kaliper atau master cylinder juga dicek.</p>

<h2>Bodi dan Cat</h2>
<h3>Deteksi Bekas Tabrakan</h3>
<p>Paint thickness gauge digunakan untuk mengukur ketebalan cat di setiap panel. Ketebalan yang tidak merata mengindikasikan pengecatan ulang, yang berarti ada perbaikan bodi sebelumnya.</p>

<h3>Kerapatan Panel</h3>
<p>Gap antar panel yang tidak simetris mengindikasikan perbaikan setelah tabrakan atau banjir. Inspektor juga memeriksa segel karet pintu dan bagasi.</p>

<h2>Kelistrikan dan Elektronik</h2>
<p>Semua fitur elektrikal diuji: power window, AC, audio, sensor parkir, kamera mundur, lampu (termasuk lampu sein), klakson, dan wiper. Aki diuji voltase dan kapasitasnya.</p>

<h2>Dokumen Kendaraan</h2>
<p>BPKB, STNK, faktur, dan buku servis diperiksa keaslian dan konsistensinya dengan kondisi fisik kendaraan. Nomor rangka dan nomor mesin diverifikasi kesesuaiannya.</p>

<h2>Kesimpulan</h2>
<p>Pemeriksaan komprehensif 150+ poin ini membutuhkan waktu sekitar 2–3 jam dan menghasilkan laporan yang dapat menjadi bukti tertulis saat bernegosiasi atau mengajukan klaim garansi. Smart Otto menyediakan laporan digital berformat PDF dengan foto per poin pemeriksaan.</p>
HTML,
            ],

            // ── Artikel 3 ─────────────────────────────────────────
            [
                'title'        => 'Kenapa Inspeksi Independen Lebih Objektif dari Cek Dealer Resmi?',
                'slug'         => 'kenapa-inspeksi-independen-lebih-objektif',
                'category'     => 'Tips Pembelian',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-09-15 08:30:00',
                'status'       => 'published',
                'excerpt'      => 'Dealer resmi memiliki conflict of interest yang mempengaruhi objektivitas pemeriksaan mereka. Pahami perbedaan mendasar antara inspeksi oleh penjual dan inspeksi independen sebelum Anda memutuskan.',
                'meta_title'   => 'Inspeksi Independen vs Dealer: Mana yang Lebih Objektif? — Smart Otto',
                'meta_description' => 'Perbandingan mendalam antara inspeksi kendaraan oleh dealer dan inspeksi independen. Kenapa pembeli harus selalu memilih pihak ketiga yang netral.',
                'thumbnail_url'=> 'seed/blog/tips-beli-mobil-bekas.svg',
                'cta_enabled'  => true,
                'cta_title'    => 'Dapatkan Opini Kedua yang Jujur',
                'cta_subtitle' => 'Inspektor Smart Otto tidak memiliki kepentingan dalam transaksi Anda.',
                'cta_button_text' => 'Pesan Inspeksi Independen',
                'cta_button_url'  => '/booking',
                'content'      => <<<HTML
<h2>Apa Itu Conflict of Interest dalam Inspeksi Kendaraan?</h2>
<p>Conflict of interest terjadi ketika pihak yang melakukan pemeriksaan memiliki kepentingan finansial dalam hasil pemeriksaan tersebut. Dalam konteks jual-beli mobil bekas, dealer atau penjual yang melakukan "inspeksi" kendaraannya sendiri jelas memiliki konflik kepentingan: semakin bagus laporan inspeksinya, semakin mudah dan mahal mereka bisa menjual.</p>

<h2>Bagaimana Cara Dealer Melakukan Inspeksi?</h2>
<h3>Inspeksi untuk Kepentingan Penjual</h3>
<p>Sebagian besar dealer melakukan inspeksi saat kendaraan masuk ke inventaris mereka — bukan untuk melaporkan kondisi jujur kepada pembeli, melainkan untuk mengetahui berapa biaya perbaikan yang perlu mereka keluarkan agar kendaraan terlihat layak jual.</p>

<h3>Informasi yang Disembunyikan</h3>
<p>Cacat minor hingga sedang sering tidak dilaporkan kepada calon pembeli. Bekas perbaikan bodi dipoles, bunyi-bunyian pada mesin diredam sementara, dan dokumen servis yang tidak lengkap jarang menjadi bahan diskusi.</p>

<h2>Perbedaan Fundamental Inspeksi Independen</h2>
<h3>Tidak Ada Insentif untuk Menyembunyikan Masalah</h3>
<p>Inspektor independen dibayar flat fee oleh calon pembeli. Mereka tidak mendapat bonus jika transaksi jadi atau rugi jika transaksi batal. Satu-satunya reputasi yang perlu mereka jaga adalah kejujuran laporan mereka.</p>

<h3>Standar Pelaporan yang Terstruktur</h3>
<p>Laporan independen menggunakan format baku dengan penilaian numerik atau kondisi (Baik/Perlu Perhatian/Perlu Perbaikan Segera) per komponen. Tidak ada ruang untuk menyembunyikan masalah di antara kalimat-kalimat promosi.</p>

<h3>Dapat Digunakan sebagai Alat Negosiasi</h3>
<p>Temuan dari inspeksi independen bisa langsung Anda gunakan untuk menegosiasikan harga. Misalnya, jika ditemukan kampas rem perlu ganti dan kondisi shock absorber mulai lemah, Anda bisa meminta pengurangan harga sebesar estimasi biaya perbaikan tersebut.</p>

<h2>Kapan Inspeksi Dealer Masih Berguna?</h2>
<p>Inspeksi dealer berguna hanya sebagai langkah awal — untuk menyaring kendaraan mana yang layak untuk diinspeksi lebih lanjut secara independen. Jangan pernah mengandalkan laporan dealer sebagai satu-satunya dasar keputusan pembelian.</p>

<h2>Studi Kasus: Apa yang Ditemukan Inspektor Independen yang Luput dari Dealer</h2>
<p>Dalam survei internal Smart Otto terhadap 200 kendaraan yang sudah "lulus inspeksi dealer", kami menemukan:</p>
<ul>
  <li><strong>34%</strong> memiliki bekas perbaikan bodi yang tidak diungkapkan</li>
  <li><strong>28%</strong> memiliki kode error tersimpan di ECU yang sudah dihapus</li>
  <li><strong>19%</strong> memiliki kondisi ban atau rem di bawah standar aman</li>
  <li><strong>12%</strong> memiliki ketidaksesuaian nomor rangka/mesin dengan dokumen</li>
</ul>

<h2>Kesimpulan</h2>
<p>Inspeksi oleh dealer adalah promosi berbentuk laporan teknis. Inspeksi independen adalah perlindungan nyata untuk kepentingan Anda sebagai pembeli. Keduanya tidak dapat dipertukarkan.</p>
HTML,
            ],

            // ── Artikel 4 ─────────────────────────────────────────
            [
                'title'        => 'Bagaimana Cara Membaca Laporan Inspeksi Mobil Bekas dengan Benar?',
                'slug'         => 'cara-membaca-laporan-inspeksi-mobil-bekas',
                'category'     => 'Panduan Inspeksi',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-09-22 10:00:00',
                'status'       => 'published',
                'excerpt'      => 'Menerima laporan inspeksi setebal 20 halaman bisa membingungkan jika Anda tidak tahu apa yang harus dicari. Panduan ini membantu Anda membaca, memahami, dan menggunakan laporan inspeksi secara efektif.',
                'meta_title'   => 'Cara Membaca Laporan Inspeksi Mobil Bekas — Smart Otto',
                'meta_description' => 'Panduan praktis membaca laporan inspeksi kendaraan bekas: arti kode warna, temuan kritis vs minor, dan cara menggunakannya saat negosiasi harga.',
                'thumbnail_url'=> 'seed/blog/dokumen-bpkb-stnk.svg',
                'cta_enabled'  => false,
                'cta_title'    => null,
                'cta_subtitle' => null,
                'cta_button_text' => null,
                'cta_button_url'  => null,
                'content'      => <<<HTML
<h2>Mengapa Memahami Laporan Inspeksi Itu Penting?</h2>
<p>Laporan inspeksi yang baik bisa menjadi senjata negosiasi yang ampuh — tetapi hanya jika Anda memahaminya. Banyak calon pembeli yang menerima laporan, membacanya sekilas, dan akhirnya tidak menggunakannya secara optimal saat bernegosiasi.</p>

<h2>Struktur Umum Laporan Inspeksi Profesional</h2>
<h3>Ringkasan Eksekutif</h3>
<p>Halaman pertama biasanya berisi ringkasan kondisi keseluruhan kendaraan dengan skor atau rekomendasi umum. Ini adalah bagian yang perlu dibaca pertama kali untuk memahami gambaran besar.</p>

<h3>Detail Per Komponen</h3>
<p>Bagian ini adalah inti laporan. Setiap komponen dinilai dengan status yang biasanya menggunakan sistem warna atau kode:</p>
<ul>
  <li><strong>Hijau / Baik:</strong> Komponen dalam kondisi normal, tidak butuh perhatian segera</li>
  <li><strong>Kuning / Perlu Perhatian:</strong> Ada tanda aus atau potensi masalah, pantau dalam waktu dekat</li>
  <li><strong>Merah / Perbaikan Segera:</strong> Masalah yang perlu diperbaiki sebelum atau sesaat setelah pembelian</li>
</ul>

<h3>Dokumentasi Foto</h3>
<p>Laporan berkualitas menyertakan foto untuk setiap temuan, terutama yang berstatus kuning atau merah. Foto adalah bukti yang tidak bisa dibantah saat negosiasi.</p>

<h2>Apa yang Harus Menjadi Prioritas Perhatian?</h2>
<h3>Temuan Kritis pada Keselamatan</h3>
<p>Masalah pada sistem rem, kemudi, atau ban adalah prioritas tertinggi. Kendaraan dengan temuan kritis pada komponen keselamatan tidak boleh dibeli sebelum diperbaiki.</p>

<h3>Masalah Mesin dan Transmisi</h3>
<p>Komponen drivetrain yang bermasalah adalah yang paling mahal untuk diperbaiki. Temuan kode error ECU, kebocoran, atau kompresi rendah perlu mendapat estimasi biaya dari mekanik terpercaya sebelum Anda memutuskan.</p>

<h3>Indikasi Bekas Tabrakan atau Banjir</h3>
<p>Laporan yang menyebutkan pengecatan ulang lebih dari 30% panel atau bekas air di dalam kabin mengindikasikan riwayat kecelakaan atau banjir. Ini bisa mempengaruhi nilai resale dan keandalan jangka panjang.</p>

<h2>Cara Menggunakan Laporan untuk Negosiasi</h2>
<p>Hitung estimasi biaya perbaikan dari semua temuan berstatus kuning dan merah. Ajukan penawaran harga yang telah dikurangi total estimasi tersebut, atau minta penjual memperbaiki terlebih dahulu sebelum harga final disepakati.</p>

<h2>Pertanyaan yang Tepat untuk Ditanyakan ke Inspektor</h2>
<p>Setelah membaca laporan, hubungi inspektor untuk klarifikasi. Pertanyaan berguna antara lain:</p>
<ul>
  <li>"Temuan mana yang paling mendesak diperbaiki?"</li>
  <li>"Berapa estimasi biaya perbaikan untuk semua temuan merah?"</li>
  <li>"Apakah secara keseluruhan kendaraan ini layak dibeli di harga yang diminta?"</li>
</ul>

<h2>Kesimpulan</h2>
<p>Laporan inspeksi adalah dokumen hidup yang memandu keputusan Anda. Luangkan waktu untuk membacanya secara detail, tandai temuan penting, dan gunakan sebagai fondasi negosiasi. Smart Otto menyediakan konsultasi pasca-inspeksi gratis untuk membantu Anda memahami laporan yang kami buat.</p>
HTML,
            ],

            // ── Artikel 5 ─────────────────────────────────────────
            [
                'title'        => 'Berapa Harga Wajar Mobil Bekas? Cara Menilai Harga Sebelum Membeli',
                'slug'         => 'berapa-harga-wajar-mobil-bekas',
                'category'     => 'Tips Pembelian',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-09-29 09:00:00',
                'status'       => 'published',
                'excerpt'      => 'Harga mobil bekas sangat bervariasi dan sering membingungkan. Pelajari faktor-faktor penentu harga dan cara menghitung apakah harga yang ditawarkan penjual masuk akal.',
                'meta_title'   => 'Cara Menilai Harga Wajar Mobil Bekas — Smart Otto',
                'meta_description' => 'Panduan menghitung harga wajar mobil bekas berdasarkan tahun, kilometer, kondisi, dan riwayat servis. Dilengkapi tips negosiasi efektif.',
                'thumbnail_url'=> 'seed/blog/harga-mobil-bekas.svg',
                'cta_enabled'  => true,
                'cta_title'    => 'Verifikasi Kondisi Sebelum Setuju Harga',
                'cta_subtitle' => 'Inspeksi Smart Otto memastikan harga yang Anda bayar sesuai kondisi nyata.',
                'cta_button_text' => 'Pesan Inspeksi',
                'cta_button_url'  => '/booking',
                'content'      => <<<HTML
<h2>Mengapa Harga Mobil Bekas Sulit Diprediksi?</h2>
<p>Tidak seperti mobil baru yang harganya tercantum di price list resmi, harga mobil bekas dipengaruhi banyak variabel: tahun produksi, kilometer, kondisi fisik, riwayat servis, kelengkapan dokumen, bahkan lokasi dan musim. Tanpa pengetahuan ini, Anda rentan membayar terlalu mahal atau melewatkan kesempatan bagus karena ragu.</p>

<h2>Faktor-Faktor yang Mempengaruhi Harga Mobil Bekas</h2>
<h3>Depresiasi Berdasarkan Usia</h3>
<p>Secara umum, mobil bekas terdepresiasi 15–25% di tahun pertama setelah pembelian, kemudian 10–15% per tahun berikutnya. Namun ini sangat bergantung pada merek dan model. Merek Jepang seperti Toyota dan Honda cenderung memiliki depresiasi lebih rendah.</p>

<h3>Odometer: Berapa Kilometer yang Wajar?</h3>
<p>Rata-rata kendaraan pribadi di Indonesia menempuh 15.000–20.000 km per tahun. Kendaraan dengan 80.000 km pada usia 5 tahun dianggap normal. Di bawah angka itu bisa menjadi nilai tambah; jauh di atasnya menjadi faktor pengurangan harga.</p>

<h3>Kelengkapan Dokumen Servis</h3>
<p>Buku servis lengkap di bengkel resmi bisa menambah nilai 5–15% dibanding kendaraan tanpa riwayat servis yang tercatat. Ini membuktikan kendaraan dirawat dengan baik.</p>

<h3>Kondisi Fisik dan Riwayat Kecelakaan</h3>
<p>Kendaraan dengan riwayat tabrakan minor bisa turun nilai 10–20%. Riwayat banjir, bahkan satu kali, bisa mengurangi nilai hingga 30–40% karena dampak jangka panjang pada sistem kelistrikan.</p>

<h2>Tools untuk Mengecek Harga Pasaran</h2>
<p>Beberapa platform online seperti OLX Autos, Carmudi, dan Mobil123 memberikan gambaran harga pasar. Namun perlu diingat bahwa harga listing adalah harga yang diminta penjual, bukan harga transaksi aktual — yang biasanya 5–15% lebih rendah.</p>

<h2>Rumus Sederhana Menilai Kewajaran Harga</h2>
<p>Gunakan pendekatan ini saat evaluasi:</p>
<ol>
  <li>Cari harga pasar rata-rata untuk model, tahun, dan kilometer yang sama</li>
  <li>Tambahkan nilai jika: dokumen lengkap, servis rutin di bengkel resmi, ban baru, satu tangan</li>
  <li>Kurangi nilai jika: butuh perbaikan, bekas tabrakan, dokumen tidak lengkap, odometer tinggi</li>
  <li>Bandingkan hasil estimasi Anda dengan harga yang diminta penjual</li>
</ol>

<h2>Kapan Harga Murah Justru Berbahaya?</h2>
<p>Harga yang jauh di bawah pasar seringkali bukan rezeki — melainkan pertanda masalah yang disembunyikan. Kendaraan bekas banjir, kendaraan hasil curian, atau kendaraan dengan masalah mesin besar sering dijual "murah" untuk menarik pembeli yang tidak curiga.</p>

<h2>Kesimpulan</h2>
<p>Harga yang wajar adalah harga yang mencerminkan kondisi nyata kendaraan. Satu-satunya cara untuk memastikan kondisi nyata adalah melalui inspeksi independen. Gunakan temuan inspeksi sebagai dasar penilaian nilai kendaraan — bukan hanya gambar dan deskripsi dari penjual.</p>
HTML,
            ],

            // ── Artikel 6 ─────────────────────────────────────────
            [
                'title'        => 'Apakah Odometer Mobil Bekas Bisa Dipalsukan? Ini Cara Mendeteksinya',
                'slug'         => 'odometer-mobil-bekas-dipalsukan-cara-deteksi',
                'category'     => 'Waspada Penipuan',
                'author_name'  => 'Tim Smart Otto',
                'published_at' => '2026-10-01 08:00:00',
                'status'       => 'published',
                'excerpt'      => 'Pemalsuan odometer adalah salah satu penipuan paling umum di pasar mobil bekas. Pelajari tanda-tandanya dan cara inspektor profesional mendeteksi kilometer yang sudah dimanipulasi.',
                'meta_title'   => 'Cara Deteksi Odometer Mobil Bekas Dipalsukan — Smart Otto',
                'meta_description' => 'Panduan lengkap mendeteksi pemalsuan odometer pada mobil bekas: tanda-tanda fisik, pemeriksaan digital, dan cara inspektor profesional memverifikasi kilometer asli.',
                'thumbnail_url'=> 'seed/blog/odometer-kilometer.svg',
                'cta_enabled'  => true,
                'cta_title'    => 'Jangan Tertipu Odometer Palsu',
                'cta_subtitle' => 'Inspektor Smart Otto memverifikasi kilometer asli kendaraan sebelum Anda membeli.',
                'cta_button_text' => 'Pesan Inspeksi Sekarang',
                'cta_button_url'  => '/booking',
                'content'      => <<<HTML
<h2>Seberapa Umum Pemalsuan Odometer di Indonesia?</h2>
<p>Menurut data industri, diperkirakan 15–25% mobil bekas yang diperjualbelikan di Indonesia memiliki odometer yang sudah dimanipulasi. Angka ini cukup mengkhawatirkan mengingat betapa besarnya dampak odometer terhadap harga dan persepsi kondisi kendaraan.</p>
<p>Pemalsuan odometer (rollback) dilakukan untuk membuat kendaraan terlihat lebih muda dari usianya — sehingga bisa dijual lebih mahal atau lebih cepat.</p>

<h2>Bagaimana Odometer Bisa Dipalsukan?</h2>
<h3>Odometer Mekanik (Kendaraan Tua)</h3>
<p>Pada kendaraan sebelum tahun 2000-an dengan odometer mekanik (angka putar fisik), pemalsuan dilakukan dengan membongkar dashboard dan memutar balik angka kilometer menggunakan alat khusus. Relatif mudah dilakukan dan sulit terdeteksi secara visual.</p>

<h3>Odometer Digital (Kendaraan Modern)</h3>
<p>Odometer digital tersimpan di modul ECU dan beberapa modul lainnya (BCM, instrument cluster). Pemalsuan membutuhkan perangkat pemrograman OBD yang bisa menulis ulang nilai kilometer. Namun data asli sering masih tersimpan di modul-modul lain yang tidak diubah.</p>

<h2>Tanda-Tanda Fisik Odometer Dipalsukan</h2>
<h3>Kondisi Interior Tidak Sesuai Kilometer</h3>
<p>Kendaraan dengan klaim 40.000 km tapi setir sudah aus, karpet kusam, jok terkelupas, atau tombol-tombol sudah pudar adalah tanda tidak konsisten. Kondisi fisik interior mencerminkan penggunaan nyata lebih jujur dari angka di panel.</p>

<h3>Kondisi Komponen Konsumsi</h3>
<p>Ban, kampas rem, dan filter udara memiliki umur pakai yang berkorelasi dengan kilometer. Klaim 50.000 km dengan ban asli yang sudah botak atau kampas rem yang hampir habis adalah kontradiksi yang perlu dijelaskan.</p>

<h3>Bekas Pembongkaran Dashboard</h3>
<p>Inspektor berpengalaman akan memeriksa tanda-tanda pembongkaran dashboard: bekas obeng di sekrup trim, plastik yang retak atau tidak rata, serta konektor yang terlihat pernah dicabut.</p>

<h2>Cara Profesional Mendeteksi Pemalsuan Odometer</h2>
<h3>Pemindaian Multi-Modul ECU</h3>
<p>Inspektor menggunakan scanner yang bisa membaca data dari beberapa modul sekaligus. Jika nilai kilometer di instrument cluster berbeda dengan data di ECU atau BCM, itu adalah bukti kuat pemalsuan.</p>

<h3>Riwayat Servis Berkala</h3>
<p>Buku servis atau catatan digital di sistem dealer resmi mencantumkan kilometer saat servis. Jika angka di buku servis terakhir lebih tinggi dari odometer sekarang, kasus rollback sudah terbukti.</p>

<h3>Stiker Servis di Pilar atau Kap Mesin</h3>
<p>Stiker servis yang sering ditempel di pilar A atau di dalam kap mesin mencantumkan tanggal dan kilometer servis. Ini adalah bukti fisik yang sering dilupakan penjual untuk dihapus.</p>

<h2>Apa yang Harus Dilakukan Jika Menemukan Indikasi Pemalsuan?</h2>
<p>Batalkan pembelian. Tidak ada alasan yang cukup kuat untuk tetap membeli kendaraan dengan odometer yang terbukti dimanipulasi. Pemalsuan odometer adalah tindakan ilegal dan mengindikasikan itikad buruk penjual — kemungkinan besar ada hal lain yang juga disembunyikan.</p>

<h2>Kesimpulan</h2>
<p>Odometer adalah salah satu dari banyak hal yang perlu diverifikasi secara independen sebelum membeli mobil bekas. Inspeksi profesional Smart Otto selalu mencakup verifikasi odometer menggunakan multi-module scan dan pemeriksaan riwayat servis, sehingga Anda tahu persis berapa kilometer asli kendaraan yang ingin Anda beli.</p>
HTML,
            ],

        ];
    }
}
