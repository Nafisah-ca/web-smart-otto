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

{{-- PACKAGES --}}
<section class="py-16 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-10">
        <h2 class="text-3xl font-bold text-gray-900">Paket Inspeksi</h2>
        <p class="text-gray-500 mt-2">Pilih paket yang sesuai kebutuhan kendaraan Anda</p>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @foreach($packages as $index => $pkg)
        <div class="card p-6 flex flex-col {{ $index === 1 ? 'border-primary-400 ring-2 ring-primary-500 relative' : '' }}">
            @if($index === 1)
            <div class="absolute -top-3 left-1/2 -translate-x-1/2">
                <span class="bg-primary-600 text-white text-xs font-bold px-3 py-1 rounded-full">POPULER</span>
            </div>
            @endif
            <div class="text-4xl mb-3">{{ $pkg->icon ?? '🔧' }}</div>
            <h3 class="text-xl font-bold text-gray-900">{{ $pkg->name }}</h3>
            <p class="text-gray-500 text-sm mt-2 flex-1">{{ $pkg->description }}</p>
            <div class="mt-4 pt-4 border-t border-gray-100">
                <div class="flex items-end justify-between">
                    <div>
                        <p class="text-2xl font-bold text-primary-600">{{ $pkg->formatted_price }}</p>
                        <p class="text-xs text-gray-400">Estimasi {{ $pkg->duration_estimate }} menit</p>
                    </div>
                    <a href="{{ route('booking.create', ['package' => $pkg->id]) }}" class="btn-primary btn-sm">Pilih</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>

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

@endsection
