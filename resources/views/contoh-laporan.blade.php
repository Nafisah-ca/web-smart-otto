<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contoh Laporan Inspeksi — Smart Otto</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Arial', sans-serif; font-size: 11px; color: #222; background: #f5f5f5; }
        .page { background: #fff; max-width: 794px; margin: 20px auto; padding: 36px 40px; box-shadow: 0 2px 12px rgba(0,0,0,.08); }
        /* Header */
        .header { text-align: center; border-bottom: 2px solid #c0392b; padding-bottom: 14px; margin-bottom: 18px; }
        .header h1 { font-size: 28px; font-weight: 900; color: #c0392b; letter-spacing: 2px; }
        .header p  { font-style: italic; color: #555; font-size: 11px; margin-top: 2px; }
        .title-laporan { text-align: center; font-size: 14px; font-weight: bold; text-transform: uppercase; margin-bottom: 18px; border: 1px solid #ddd; padding: 8px; background: #fafafa; }
        /* Info kendaraan */
        .info-grid { display: grid; grid-template-columns: 180px 1fr; gap: 0; }
        .foto-box  { border: 1px dashed #aaa; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #aaa; font-style: italic; font-size: 10px; padding: 16px; }
        .foto-box svg { width: 36px; height: 36px; color: #bbb; }
        .info-table { border: 1px solid #ddd; border-collapse: collapse; width: 100%; }
        .info-table td { padding: 5px 10px; border-bottom: 1px solid #eee; vertical-align: middle; }
        .info-table td:first-child { font-weight: bold; width: 130px; }
        /* Section headers */
        .section-header { background: #c0392b; color: #fff; font-weight: bold; padding: 5px 10px; margin: 18px 0 0 0; font-size: 11px; text-transform: uppercase; }
        /* Ringkasan cepat */
        .ringkasan-grid { display: grid; grid-template-columns: repeat(6, 1fr); border: 1px solid #ddd; }
        .ringkasan-item { text-align: center; padding: 8px 4px; border-right: 1px solid #ddd; }
        .ringkasan-item:last-child { border-right: none; }
        .ringkasan-item .label { font-size: 10px; font-weight: bold; color: #333; }
        .ringkasan-item .status { font-size: 10px; margin-top: 3px; }
        .status-ok      { color: #27ae60; }
        .status-warning { color: #e67e22; }
        /* Catatan montir */
        .catatan-table { width: 100%; border-collapse: collapse; border: 1px solid #ddd; }
        .catatan-table td { padding: 5px 10px; border-bottom: 1px solid #eee; }
        .catatan-table td:first-child { font-weight: bold; width: 55%; }
        .catatan-table .status-ganti { color: #c0392b; font-weight: bold; }
        .lain-lain { padding: 10px; border: 1px solid #ddd; border-top: none; }
        .lain-lain ul { padding-left: 18px; }
        .lain-lain li { margin-bottom: 3px; }
        /* Checklist */
        .checklist-section { margin-top: 8px; }
        .legend { border: 1px solid #ddd; padding: 6px 10px; margin-bottom: 10px; font-size: 10px; display: flex; gap: 16px; align-items: center; }
        .dot { display: inline-block; width: 10px; height: 10px; border-radius: 50%; margin-right: 3px; }
        .dot-ok      { background: #27ae60; }
        .dot-servis  { background: #e67e22; }
        .dot-ganti   { background: #c0392b; }
        .dot-na      { background: #bbb; }
        .checklist-cat-header { background: #c0392b; color: #fff; font-weight: bold; padding: 4px 10px; margin-top: 10px; font-size: 10.5px; }
        .checklist-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 0; }
        .checklist-row { display: flex; justify-content: space-between; align-items: center; padding: 4px 10px; border-bottom: 1px solid #f0f0f0; font-size: 10.5px; }
        .checklist-row:nth-child(even) { background: #fafafa; }
        /* Detail ban */
        .ban-table { width: 100%; border-collapse: collapse; border: 1px solid #ddd; margin-top: 8px; }
        .ban-table th { background: #f0f0f0; font-weight: bold; padding: 5px 10px; text-align: left; border-bottom: 1px solid #ddd; font-size: 10px; }
        .ban-table td { padding: 5px 10px; border-bottom: 1px solid #eee; font-size: 10px; }
        /* TTD */
        .ttd-section { border: 1px solid #ddd; margin-top: 18px; }
        .ttd-header  { background: #c0392b; color: #fff; text-align: center; font-weight: bold; padding: 6px; font-size: 12px; text-transform: uppercase; }
        .ttd-body    { padding: 14px 20px; }
        .ttd-grid    { display: grid; grid-template-columns: 1fr 1fr; gap: 30px; margin-top: 40px; }
        .ttd-person  { text-align: center; border-top: 1px solid #333; padding-top: 6px; }
        .ttd-person p { font-size: 10px; font-style: italic; }
        /* Foto grid */
        .foto-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 10px; }
        .foto-item { border: 1px dashed #aaa; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; color: #aaa; font-style: italic; font-size: 10px; padding: 30px 20px; min-height: 120px; }
        .section-title-big { font-size: 14px; color: #c0392b; font-weight: bold; margin: 18px 0 8px; }
        /* Print */
        @media print {
            body { background: #fff; }
            .page { box-shadow: none; margin: 0; max-width: 100%; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

{{-- Print / Kembali buttons --}}
<div class="no-print" style="max-width:794px;margin:16px auto;display:flex;gap:10px;padding:0 10px;">
    <button onclick="window.print()" style="padding:8px 18px;background:#c0392b;color:#fff;border:none;border-radius:6px;cursor:pointer;font-size:12px;">🖨️ Cetak / Simpan PDF</button>
    <a href="{{ route('home') }}" style="padding:8px 18px;background:#f0f0f0;color:#333;border:none;border-radius:6px;cursor:pointer;font-size:12px;text-decoration:none;">← Kembali ke Beranda</a>
</div>

<div class="page">
    {{-- HALAMAN 1 --}}
    <div class="header">
        <h1>SMART OTTO</h1>
        <p>Layanan Inspeksi Kendaraan Terpercaya</p>
    </div>
    <div class="title-laporan">LAPORAN PENGECEKAN KENDARAAN</div>

    <div class="info-grid">
        <div class="foto-box">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1">
                <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                <circle cx="12" cy="13" r="4"/>
            </svg>
            <em>Foto Kendaraan</em>
        </div>
        <table class="info-table">
            <tr><td>Merk</td><td>: Toyota</td></tr>
            <tr><td>Model</td><td>: Avanza</td></tr>
            <tr><td>No. Polisi</td><td>: B 1234 XYZ</td></tr>
            <tr><td>Nama Customer</td><td>: Budi Santoso</td></tr>
            <tr><td>Tgl Pengecekan</td><td>: 29 September 2026</td></tr>
            <tr><td>Tahun Mobil</td><td>: 2019</td></tr>
            <tr><td>Kilometer</td><td>: 68.500</td></tr>
            <tr><td>Inspektor</td><td>: Ahmad Fauzi</td></tr>
        </table>
    </div>

    <div class="section-header">Ringkasan Cepat</div>
    <div class="ringkasan-grid">
        <div class="ringkasan-item"><div class="label">Mesin</div><div class="status status-warning">⚠ Perlu Diservis</div></div>
        <div class="ringkasan-item"><div class="label">Suspensi</div><div class="status status-ok">✓ OK</div></div>
        <div class="ringkasan-item"><div class="label">Transmisi</div><div class="status status-ok">✓ OK</div></div>
        <div class="ringkasan-item"><div class="label">AC</div><div class="status status-ok">✓ OK</div></div>
        <div class="ringkasan-item"><div class="label">Bebas Banjir</div><div class="status status-ok">✓ OK</div></div>
        <div class="ringkasan-item"><div class="label">Bebas Tabrakan</div><div class="status status-ok">✓ OK</div></div>
    </div>

    <div class="section-header" style="margin-top:14px;">Catatan Montir</div>
    <table class="catatan-table">
        <tr><td>Kondisi Ban Depan Kiri</td><td>Baik</td></tr>
        <tr><td>Kondisi Ban Depan Kanan</td><td class="status-ganti">Ganti</td></tr>
        <tr><td>Kondisi Ban Belakang Kiri</td><td>Baik</td></tr>
        <tr><td>Kondisi Ban Belakang Kanan</td><td>Baik</td></tr>
        <tr><td>Kondisi Ban Serep</td><td>Baik</td></tr>
    </table>
    <div class="lain-lain">
        <strong>Lain-lain:</strong>
        <ul style="margin-top:5px;">
            <li>Kampas rem depan mulai tipis</li>
            <li>Karet wiper depan sudah getas</li>
            <li>Aki menunjukkan tegangan lemah, disarankan ganti</li>
            <li>Terdapat rembesan oli ringan di sekitar mesin</li>
            <li>Lampu plat nomor belakang mati</li>
            <li>Buku servis tidak tersedia saat pengecekan</li>
            <li>Kotak P3K &amp; segitiga pengaman tidak ada</li>
        </ul>
    </div>

    {{-- HALAMAN 2: CHECKLIST --}}
    <div style="margin-top:28px;">
        <div class="section-header" style="margin:0;">Checklist Detail</div>
        <div class="legend">
            <span><span class="dot dot-ok"></span> OK</span>
            <span><span class="dot dot-servis"></span> Perlu Diservis</span>
            <span><span class="dot dot-ganti"></span> Perlu Diganti</span>
            <span><span class="dot dot-na"></span> Tidak Tersedia</span>
        </div>

        @php
        $checklist = [
            'A. Interior Kendaraan' => [
                'Fungsi remote entry system','Sistem penguncian pintu','Cek performa Air Conditioning System',
                'Fungsi Radio/Bluetooth speaker','Kondisi setir & tombol kemudi','Fungsi rem parkir',
                'Fungsi pedal gas, rem, kopling','Indikator panel instrumen','Fungsi klakson',
                'Penyemprotan air wiper','Kondisi jok kursi','Fungsi penyetelan kursi',
                'Kondisi & fungsi sabuk pengaman','Kondisi trim & panel pintu','Kondisi karpet dasar & lantai',
                'Fungsi tombol pembuka kap depan','Kondisi karpet ruang bagasi','Kondisi dongkrak & tool kit',
            ],
            'B. Scan Diagnostic' => [
                'Engine (scan tool)','Transmission (scan tool)','ABS (scan tool)',
                'Airbag (scan tool)','BCM (scan tool)',
            ],
            'C. Ruang Mesin' => [
                'Kondisi oli mesin','Kondisi radiator coolant','Kondisi cairan rem',
                'Kondisi oli power steering','Kondisi cairan wiper','Tidak terdapat kebocoran oli mesin',
                'Tidak ada kebocoran oli transmisi','Kondisi selang-selang & klem','Kondisi tali kipas',
                'Fungsi dinamo starter','Kondisi aki','Fungsi alternator / pengisian',
                'Kondisi engine mounting (visual)','Kondisi master rem (visual)','Fungsi compressor AC','Kondisi radiator',
            ],
            'D. Exterior Kendaraan' => [
                'Tidak ada indikasi bekas banjir','Tidak ada indikasi bekas kecelakaan besar',
                'Inspeksi panel body','Inspeksi bumper depan','Inspeksi bumper belakang',
                'Kondisi grille depan','Kondisi trim (list mobil)','Kondisi pintu','Kondisi kap depan',
                'Kondisi atap','Kondisi kaca depan/samping/belakang','Fungsi wiper depan & belakang',
                'Kondisi spion','Kondisi & fungsi lampu headlight','Kondisi & fungsi lampu belakang/stop',
                'Kondisi & fungsi lampu sein','Fungsi lampu hazard','Kondisi lampu plat nomor',
            ],
            'E. Kaki-kaki dan Ban' => [
                'Kondisi Rack & Pinion boots','Kondisi CV boots','Kondisi Swing Arm bushing',
                'Kondisi per & shock absorber','Brake pad depan (rem cakram)','Brake pad belakang',
                'Kondisi piringan cakram depan & belakang',
            ],
            'F. Test Drive' => [
                'Mesin menyala dengan baik','Mesin idle dengan baik','Akselerasi mesin saat berjalan',
                'Suara mesin normal','Perpindahan gigi transmisi normal','Kopling berfungsi normal',
                'Steer normal','Suara suspensi normal','Performa rem normal','Gauge dashboard berfungsi normal',
            ],
            'G. Sejarah dan Surat' => [
                'Riwayat perawatan berkala','Stiker uji emisi','Kunci serep tersedia',
                'Kunci remote control tersedia','No. rangka sesuai dokumen',
                'No. mesin sesuai dokumen','Buku manual & servis tersedia',
            ],
        ];
        $naItems = ['Kondisi dongkrak & tool kit','Riwayat perawatan berkala','Stiker uji emisi','Buku manual & servis tersedia'];
        $warnItems = ['Kondisi oli mesin'];
        @endphp

        @foreach($checklist as $cat => $items)
        <div class="checklist-cat-header">{{ $cat }}</div>
        <div class="checklist-grid">
            @foreach($items as $item)
            <div class="checklist-row">
                <span>{{ $loop->iteration + ($loop->parent->index * 0) }}. {{ $item }}</span>
                @if(in_array($item, $naItems))
                    <span class="dot dot-na" style="flex-shrink:0;"></span>
                @elseif(in_array($item, $warnItems))
                    <span class="dot dot-servis" style="flex-shrink:0;"></span>
                @else
                    <span class="dot dot-ok" style="flex-shrink:0;"></span>
                @endif
            </div>
            @endforeach
        </div>
        @endforeach
    </div>

    {{-- Detail Kondisi Ban --}}
    <div style="margin-top:18px;">
        <div style="font-weight:bold;color:#c0392b;font-size:12px;margin-bottom:6px;">Detail Kondisi Ban</div>
        <table class="ban-table">
            <thead>
                <tr><th>Posisi</th><th>Merk & Ukuran</th><th>Kode Produksi</th></tr>
            </thead>
            <tbody>
                <tr><td>Depan Kiri</td><td>Bridgestone 205/65 R15</td><td>0221-2021</td></tr>
                <tr><td>Depan Kanan</td><td>Bridgestone 205/65 R15</td><td>0221-2021</td></tr>
                <tr><td>Belakang Kiri</td><td>Dunlop 205/65 R15</td><td>3520-2020</td></tr>
                <tr><td>Belakang Kanan</td><td>Dunlop 205/65 R15</td><td>3520-2020</td></tr>
                <tr><td>Serep</td><td>Bridgestone 205/65 R15</td><td>1119-2019</td></tr>
            </tbody>
        </table>
    </div>

    {{-- Foto Kendaraan --}}
    <div style="margin-top:22px;">
        <div class="section-title-big">Foto Kendaraan</div>
        <div class="foto-grid">
            @foreach(['Eksterior Depan','Eksterior Belakang','Eksterior Kanan','Eksterior Kiri','Interior Dashboard','Interior Kursi Depan','Ruang Mesin','Nomor Rangka & Mesin'] as $label)
            <div class="foto-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" width="28" height="28">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <em>{{ $label }}</em>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Foto Kerusakan --}}
    <div style="margin-top:22px;">
        <div class="section-title-big">Foto Kerusakan</div>
        <div class="foto-grid">
            @foreach(['Kampas Rem Depan Tipis','Karet Wiper Getas','Rembesan Oli Mesin','Lampu Plat Nomor Mati'] as $label)
            <div class="foto-item">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1" width="28" height="28">
                    <path d="M23 19a2 2 0 01-2 2H3a2 2 0 01-2-2V8a2 2 0 012-2h4l2-3h6l2 3h4a2 2 0 012 2z"/>
                    <circle cx="12" cy="13" r="4"/>
                </svg>
                <em>{{ $label }}</em>
            </div>
            @endforeach
        </div>
    </div>

    {{-- TANDA TANGAN --}}
    <div class="ttd-section" style="margin-top:22px;">
        <div class="ttd-header">Tanda Tangan</div>
        <div class="ttd-body">
            <p>✓ <strong>Inspeksi sudah dicek dengan baik dan benar</strong></p>
            <p style="margin-top:4px;">Tanggal: 29 September 2026</p>
            <div class="ttd-grid">
                <div class="ttd-person">
                    <strong>Ahmad Fauzi</strong>
                    <p>Tanda Tangan Mekanik</p>
                </div>
                <div class="ttd-person">
                    <strong>Budi Santoso</strong>
                    <p>Tanda Tangan Customer</p>
                </div>
            </div>
            <p style="margin-top:18px;font-size:9px;color:#888;font-style:italic;">
                *Laporan ini hanya berlaku saat pengecekan. Kami tidak bertanggung jawab atas perbedaan kondisi yang terjadi setelah dilakukan pengecekan.
            </p>
        </div>
    </div>

</div><!-- end .page -->

</body>
</html>
