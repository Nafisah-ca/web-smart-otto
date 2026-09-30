@extends('layouts.app')
@section('title', 'Beranda')
@section('content')

{{-- HERO --}}
@php
    $heroBg = $cms['hero_image'] ?? '';
    $heroBgStyle = $heroBg
        ? 'background-image: url(' . asset($heroBg) . '); background-size: cover; background-position: center;'
        : '';
@endphp
<section class="relative text-white overflow-hidden {{ $heroBg ? '' : 'bg-gradient-to-br from-gray-900 via-gray-800 to-primary-900' }}"
         @if($heroBg) style="{{ $heroBgStyle }}" @endif>
    {{-- Overlay gelap saat ada gambar --}}
    @if($heroBg)
    <div class="absolute inset-0 bg-black/55"></div>
    @else
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-0 left-0 w-96 h-96 bg-primary-500 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-primary-400 rounded-full translate-x-1/2 translate-y-1/2 blur-3xl"></div>
    </div>
    @endif
    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-28">
        <div class="max-w-2xl">
            <div class="inline-flex items-center gap-2 bg-primary-600/20 border border-primary-500/30 rounded-full px-4 py-1.5 text-sm text-primary-300 mb-6">
                <span class="w-2 h-2 bg-primary-400 rounded-full animate-pulse"></span>
                {{ $cms['hero_badge'] }}
            </div>
            <h1 class="text-4xl md:text-5xl font-extrabold leading-tight mb-5">
                {{ $cms['hero_title'] ?? 'Inspeksi Kendaraan Anda dengan Teknisi Berpengalaman' }}
            </h1>
            <p class="text-lg text-gray-300 leading-relaxed mb-8">
                {{ $cms['hero_subtitle'] ?? 'Smart Otto hadir untuk memastikan kendaraan Anda dalam kondisi prima.' }}
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('booking.create') }}" class="btn-primary text-base px-7 py-3.5">
                    {{ $cms['hero_cta_text'] ?? 'Booking Sekarang' }}
                </a>
                <a href="{{ route('layanan') }}" class="btn-secondary text-base px-7 py-3.5 bg-white/10 border-white/30 text-white hover:bg-white/20">
                    Lihat Paket
                </a>
            </div>
        </div>
    </div>
</section>

{{-- STATS --}}
<section class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @php
            $statItems = [
                ['value' => $cms['stat_customers'],   'label' => 'Customer Puas',    'icon' => $cms['stat_icon_customers']],
                ['value' => $cms['stat_inspections'],  'label' => 'Inspeksi Selesai', 'icon' => $cms['stat_icon_inspections']],
                ['value' => $cms['stat_inspectors'],   'label' => 'Teknisi Aktif',    'icon' => $cms['stat_icon_inspectors']],
                ['value' => $cms['stat_years'],        'label' => 'Tahun Pengalaman', 'icon' => $cms['stat_icon_years']],
            ];
            @endphp
            @foreach($statItems as $stat)
            <div class="flex flex-col items-center">
                @if(!empty($stat['icon']))
                <img src="{{ asset($stat['icon']) }}"
                     alt="{{ $stat['label'] }}" class="w-10 h-10 object-contain mb-2">
                @endif
                <p class="text-3xl font-extrabold text-primary-600">{{ $stat['value'] }}</p>
                <p class="text-sm text-gray-500 mt-1">{{ $stat['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KATEGORI KENDARAAN --}}
@if(!empty($cms['kategori_items']))
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ $cms['kategori_title'] ?? 'Pilih Kategori' }}</h2>

    <div class="flex gap-6">
        {{-- KIRI: Tab kategori --}}
        <div class="flex flex-col gap-2 w-36 flex-shrink-0">
            @foreach($cms['kategori_items'] as $i => $kat)
            <button onclick="switchKat({{ $i }})"
                    id="tab-{{ $i }}"
                    class="kat-tab flex items-center justify-between px-4 py-2.5 rounded-lg border text-sm font-medium transition-all text-left
                           {{ $i === 0 ? 'bg-primary-600 text-white border-primary-600' : 'bg-white text-gray-700 border-gray-200 hover:border-primary-400' }}">
                <span>{{ $kat['name'] }}</span>
                <svg class="w-4 h-4 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
            @endforeach
        </div>

        {{-- TENGAH + KANAN: Konten per kategori --}}
        <div class="flex-1 bg-white border border-gray-200 rounded-xl overflow-hidden">
            @foreach($cms['kategori_items'] as $i => $kat)
            <div id="panel-{{ $i }}" class="kat-panel {{ $i !== 0 ? 'hidden' : '' }}">
                <div class="flex flex-col md:flex-row">
                    {{-- Gambar --}}
                    <div class="md:w-1/2 flex items-center justify-center p-8 bg-gray-50" style="min-height:260px;max-height:320px;overflow:hidden;">
                        @if(!empty($kat['img']))
                        <img src="{{ asset($kat['img']) }}" alt="{{ $kat['name'] }}"
                             class="w-full h-full object-contain" style="max-height:260px;">
                        @else
                        <div class="flex items-center justify-center w-full" style="height:200px;">
                            <svg class="w-24 h-24 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1"
                                      d="M8 17a2 2 0 100-4 2 2 0 000 4zm8 0a2 2 0 100-4 2 2 0 000 4zM3 11l1.5-4.5A2 2 0 016.4 5h11.2a2 2 0 011.9 1.5L21 11v4a1 1 0 01-1 1h-1M3 11v4a1 1 0 001 1h1m-2-5h18"/>
                            </svg>
                        </div>
                        @endif
                    </div>

                    {{-- Daftar model --}}
                    <div class="md:w-1/2 border-l border-gray-100">
                        @if($kat['harga'])
                        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-800">{{ $kat['harga'] }}</p>
                        </div>
                        @endif
                        @if(!empty($kat['models']))
                        @php
                            $models = $kat['models'];
                            $half   = (int) ceil(count($models) / 2);
                            $col1   = array_slice($models, 0, $half);
                            $col2   = array_slice($models, $half);
                        @endphp
                        <div class="grid grid-cols-2 divide-x divide-gray-100">
                            <ul>
                                @foreach($col1 as $model)
                                <li class="px-5 py-2.5 text-sm text-gray-700 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">{{ $model }}</li>
                                @endforeach
                            </ul>
                            <ul>
                                @foreach($col2 as $model)
                                <li class="px-5 py-2.5 text-sm text-gray-700 border-b border-gray-100 last:border-b-0 hover:bg-gray-50 transition-colors">{{ $model }}</li>
                                @endforeach
                            </ul>
                        </div>
                        @else
                        <div class="p-6 text-center text-sm text-gray-400">Belum ada model tersedia.</div>
                        @endif

                        <div class="px-6 py-4 border-t border-gray-100">
                            <a href="{{ route('booking.create') }}" class="btn-primary btn-sm w-full justify-center">
                                Booking Sekarang
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- HOW IT WORKS --}}
<section class="bg-gray-50 py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10">
            <h2 class="text-3xl font-bold text-gray-900">{{ $cms['cara_kerja_title'] }}</h2>
            @if($cms['cara_kerja_subtitle'])
            <p class="text-gray-500 mt-2 max-w-xl mx-auto">{{ $cms['cara_kerja_subtitle'] }}</p>
            @endif
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            @forelse($cms['cara_kerja_steps'] as $i => $step)
            <div class="text-center">
                <div class="w-14 h-14 bg-primary-50 rounded-2xl flex items-center justify-center mx-auto mb-4 border border-primary-100">
                    @if(!empty($step['icon']))
                    <img src="{{ asset($step['icon']) }}"
                         alt="{{ $step['title'] ?? '' }}" class="w-8 h-8 object-contain">
                    @else
                    <svg class="w-7 h-7 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    @endif
                </div>
                <div class="w-7 h-7 bg-primary-600 rounded-full flex items-center justify-center text-white text-sm font-bold mx-auto -mt-10 mb-4 relative z-10 border-2 border-white">{{ $step['step'] ?? $i + 1 }}</div>
                <h3 class="font-semibold text-gray-900">{{ $step['title'] ?? '' }}</h3>
                <p class="text-sm text-gray-500 mt-1">{{ $step['desc'] ?? '' }}</p>
            </div>
            @empty
            <div class="col-span-4 text-center text-gray-400 py-8">Belum ada langkah cara kerja.</div>
            @endforelse
        </div>
    </div>
</section>

{{-- KEUNGGULAN --}}
@if(!empty($cms['keunggulan_items']))
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900">{{ $cms['keunggulan_title'] }}</h2>
        @if($cms['keunggulan_subtitle'])
        <p class="text-gray-500 mt-2 max-w-xl mx-auto">{{ $cms['keunggulan_subtitle'] }}</p>
        @endif
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($cms['keunggulan_items'] as $item)
        <div class="card p-6 flex gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-primary-100">
                @if(!empty($item['icon']))
                <img src="{{ asset($item['icon']) }}"
                     alt="{{ $item['title'] ?? '' }}" class="w-7 h-7 object-contain">
                @else
                <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"/>
                </svg>
                @endif
            </div>
            <div>
                <h3 class="font-semibold text-gray-900 text-sm">{{ $item['title'] ?? '' }}</h3>
                <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $item['desc'] ?? '' }}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

{{-- FAQ --}}
@if(!empty($cms['faq_items']))
<section class="py-16 max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900">Pertanyaan Umum</h2>
    </div>
    <div class="space-y-4">
        @foreach($cms['faq_items'] as $faq)
        <details class="card p-5 group">
            <summary class="flex items-center justify-between cursor-pointer font-medium text-gray-800 list-none">
                {{ $faq['q'] }}
                <svg class="w-5 h-5 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </summary>
            <p class="text-gray-600 text-sm mt-3 leading-relaxed">{{ $faq['a'] }}</p>
        </details>
        @endforeach
    </div>
</section>
@endif

{{-- CTA BOTTOM --}}
<section class="bg-primary-600 py-14">
    <div class="max-w-7xl mx-auto px-4 text-center">
        <h2 class="text-3xl font-bold text-white mb-4">{{ $cms['cta_title'] }}</h2>
        <p class="text-primary-100 mb-7">{{ $cms['cta_subtitle'] }}</p>
        <a href="{{ route('booking.create') }}" class="inline-flex items-center gap-2 bg-white text-primary-700 font-bold px-8 py-3.5 rounded-xl hover:bg-primary-50 transition-colors text-base">
            {{ $cms['cta_button_text'] }}
        </a>
    </div>
</section>

@push('scripts')
<script>
function switchKat(idx) {
    // Sembunyikan semua panel
    document.querySelectorAll('.kat-panel').forEach(p => p.classList.add('hidden'));
    // Reset semua tab
    document.querySelectorAll('.kat-tab').forEach(t => {
        t.classList.remove('bg-primary-600','text-white','border-primary-600');
        t.classList.add('bg-white','text-gray-700','border-gray-200');
    });
    // Tampilkan panel yang dipilih
    document.getElementById('panel-' + idx)?.classList.remove('hidden');
    // Aktifkan tab yang dipilih
    const tab = document.getElementById('tab-' + idx);
    if (tab) {
        tab.classList.remove('bg-white','text-gray-700','border-gray-200');
        tab.classList.add('bg-primary-600','text-white','border-primary-600');
    }
}
</script>
@endpush

@endsection
