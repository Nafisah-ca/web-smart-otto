@extends('layouts.app')
@section('title', 'Layanan Kami')
@section('content')
<div class="pb-16">
    <header class="mx-auto max-w-7xl px-4 pb-10 pt-16 text-center sm:px-6 sm:pt-20 lg:px-8">
        <p class="text-xs font-bold uppercase tracking-[0.2em] text-primary-600">Inspeksi Kendaraan Profesional</p>
        <h1 class="mx-auto mt-3 max-w-3xl text-3xl font-extrabold leading-tight text-gray-900 sm:text-4xl md:text-5xl">
            Kenali Kondisi Kendaraan Sebelum Memutuskan
        </h1>
        <p class="mx-auto mt-4 max-w-2xl text-base leading-relaxed text-gray-500 sm:text-lg">
            Pemeriksaan menyeluruh oleh teknisi bersertifikat dengan alat diagnostik modern dan laporan yang mudah dipahami.
        </p>
    </header>

    {{-- Highlight layanan --}}
    <div class="mx-auto grid max-w-7xl grid-cols-1 gap-4 px-4 sm:grid-cols-3 sm:px-6 lg:px-8">
        @foreach([
            ['title'=>'81 Poin Pemeriksaan', 'desc'=>'Dari interior hingga test drive, setiap komponen dicek secara menyeluruh dan terdokumentasi.'],
            ['title'=>'Laporan Digital', 'desc'=>'Laporan lengkap dikirim ke dashboard Anda. Bisa diunduh dan dicetak kapan saja.'],
            ['title'=>'Teknisi Bersertifikat', 'desc'=>'Tim inspektor berpengalaman dan terlatih, tidak berafiliasi dengan dealer atau penjual kendaraan.'],
        ] as $f)
        <article class="rounded-2xl border border-gray-100 bg-white p-6 shadow-lg shadow-gray-200/50 transition duration-200 hover:-translate-y-1 hover:shadow-xl">
            <div class="mb-4 flex h-11 w-11 items-center justify-center rounded-xl bg-primary-50 text-primary-600">
                @if($loop->index === 0)
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m14.7 6.3 3 3M4 20l4.5-1 10.2-10.2a2.1 2.1 0 0 0-3-3L5.5 16 4 20zM13 8l3 3"/>
                </svg>
                @elseif($loop->index === 1)
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M7 3.75h7l4.25 4.25v12.25H7A2.25 2.25 0 0 1 4.75 18V6A2.25 2.25 0 0 1 7 3.75zM14 4v4h4M8.5 13h5M8.5 16.5h6"/>
                </svg>
                @else
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3 5 6v5c0 4.4 2.9 8.1 7 9.5 4.1-1.4 7-5.1 7-9.5V6l-7-3z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9 12 2 2 4-4"/>
                </svg>
                @endif
            </div>
            <h2 class="mb-2 font-bold text-gray-900">{{ $f['title'] }}</h2>
            <p class="text-sm leading-relaxed text-gray-500">{{ $f['desc'] }}</p>
        </article>
        @endforeach
    </div>

    <div class="mx-auto max-w-7xl px-4 pt-16 sm:px-6 lg:px-8">
        {{-- 7 Kategori Checklist --}}
        <section class="mb-16">
            <div class="mb-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-primary-600">Proses inspeksi</span>
                <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">81 Poin Pemeriksaan</h2>
                <p class="mx-auto mt-3 max-w-2xl leading-relaxed text-gray-500">Dikelompokkan dalam 7 kategori agar hasil pemeriksaan terstruktur dan mudah dipahami.</p>
            </div>

            @php
            $kategoriChecklist = [
                ['kode'=>'A', 'nama'=>'Interior Kendaraan', 'jumlah'=>18,
                 'poin'=>['Remote entry system','Penguncian pintu','Air Conditioning','Audio/Bluetooth','Setir & tombol kemudi','Rem parkir','Pedal gas/rem/kopling','Panel instrumen','Klakson','Wiper','Jok kursi','Sabuk pengaman','Trim & panel pintu','Karpet lantai','Pembuka kap','Karpet bagasi','Dongkrak & tool kit']],
                ['kode'=>'B', 'nama'=>'Scan Diagnostic', 'jumlah'=>5,
                 'poin'=>['Engine (OBD scan)','Transmission (OBD scan)','ABS (OBD scan)','Airbag (OBD scan)','BCM (OBD scan)']],
                ['kode'=>'C', 'nama'=>'Ruang Mesin', 'jumlah'=>16,
                 'poin'=>['Oli mesin','Radiator coolant','Cairan rem','Oli power steering','Cairan wiper','Kebocoran oli','Kebocoran transmisi','Selang & klem','Tali kipas','Dinamo starter','Aki','Alternator','Engine mounting','Master rem','Compressor AC','Radiator']],
                ['kode'=>'D', 'nama'=>'Exterior Kendaraan', 'jumlah'=>18,
                 'poin'=>['Indikasi bekas banjir','Indikasi bekas tabrakan','Panel body','Bumper depan/belakang','Grille depan','Trim','Pintu','Kap depan','Atap','Kaca','Wiper','Spion','Lampu headlight','Lampu belakang','Lampu sein','Lampu hazard','Lampu plat nomor']],
                ['kode'=>'E', 'nama'=>'Kaki-kaki dan Ban', 'jumlah'=>7,
                 'poin'=>['Rack & Pinion boots','CV boots','Swing Arm bushing','Per & shock absorber','Brake pad depan','Brake pad belakang','Piringan cakram']],
                ['kode'=>'F', 'nama'=>'Test Drive', 'jumlah'=>10,
                 'poin'=>['Mesin menyala','Mesin idle','Akselerasi','Suara mesin','Perpindahan gigi','Kopling','Kemudi','Suara suspensi','Performa rem','Gauge dashboard']],
                ['kode'=>'G', 'nama'=>'Sejarah dan Surat', 'jumlah'=>7,
                 'poin'=>['Riwayat perawatan','Stiker uji emisi','Kunci serep','Kunci remote','No. rangka','No. mesin','Buku manual & servis']],
            ];
            @endphp

            <div class="grid grid-cols-1 gap-5 md:grid-cols-2">
                @foreach($kategoriChecklist as $kat)
                <article class="rounded-2xl border border-gray-100 bg-white p-5 shadow-sm transition duration-200 hover:border-primary-100 hover:shadow-md sm:p-6">
                    <div class="mb-4 flex items-center gap-3">
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-primary-50 text-sm font-extrabold text-primary-700">
                            {{ $kat['kode'] }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <h3 class="font-bold text-gray-900">{{ $kat['nama'] }}</h3>
                            <p class="mt-0.5 text-xs text-gray-500">{{ $kat['jumlah'] }} poin pemeriksaan</p>
                        </div>
                        <svg class="h-5 w-5 flex-shrink-0 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m5 12 4 4L19 6"/>
                        </svg>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @foreach($kat['poin'] as $p)
                        <span class="rounded-lg bg-gray-50 px-2.5 py-1 text-xs leading-relaxed text-gray-600 ring-1 ring-inset ring-gray-100">{{ $p }}</span>
                        @endforeach
                    </div>
                </article>
                @endforeach
            </div>
        </section>

        {{-- Harga berdasarkan kategori kendaraan --}}
        <section class="mb-16">
            <div class="mb-8 text-center">
                <span class="text-xs font-bold uppercase tracking-[0.2em] text-primary-600">Transparan & fleksibel</span>
                <h2 class="mt-2 text-2xl font-extrabold text-gray-900 sm:text-3xl">Estimasi Harga</h2>
                <p class="mx-auto mt-3 max-w-2xl leading-relaxed text-gray-500">Harga inspeksi menyesuaikan kategori kendaraan. Admin akan mengonfirmasi harga final setelah booking.</p>
            </div>

            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($vehicleCategories as $cat)
                <article class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white p-6 shadow-sm transition duration-200 hover:-translate-y-1 hover:border-primary-200 hover:shadow-lg">
                    <div class="absolute inset-x-0 top-0 h-1 {{ $loop->index === 0 ? 'bg-primary-600' : 'bg-gray-100 group-hover:bg-primary-200' }} transition-colors"></div>
                    <div class="mb-5 flex items-start justify-between gap-3">
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wider text-gray-400">Kategori kendaraan</p>
                            <h3 class="mt-1 text-lg font-bold text-gray-900">{{ $cat->name }}</h3>
                        </div>
                        <span class="flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl bg-gray-50 text-primary-600">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 13h2l2-5h10l2 5h2v5h-2m-14 0H3v-5zm3 0h8m-8 0a2 2 0 1 0 4 0m4 0a2 2 0 1 0 4 0M7 8l1-3h8l1 3"/>
                            </svg>
                        </span>
                    </div>
                    <p class="text-2xl font-extrabold text-primary-600">{{ $cat->formatted_range }}</p>
                    @if($cat->description)
                    <p class="mt-2 text-sm leading-relaxed text-gray-500">{{ $cat->description }}</p>
                    @endif
                    <div class="mt-5 border-t border-gray-100 pt-4">
                        <p class="mb-2 text-xs font-semibold uppercase tracking-wide text-gray-400">Contoh model</p>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($cat->modelKeywords->take(8) as $m)
                            <span class="rounded-md bg-gray-50 px-2 py-1 text-xs text-gray-600">{{ $m->model_keyword }}</span>
                            @endforeach
                            @if($cat->modelKeywords->count() > 8)
                            <span class="px-1 py-1 text-xs font-medium text-primary-600">+{{ $cat->modelKeywords->count() - 8 }} lainnya</span>
                            @endif
                        </div>
                    </div>
                </article>
                @endforeach
            </div>
            <p class="mt-5 text-center text-sm text-gray-500">Model tidak ada dalam daftar? Admin akan menentukan kategori dan harga sesuai spesifikasi kendaraan.</p>
        </section>

    </div>
</div>
@endsection
