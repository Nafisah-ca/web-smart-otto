@extends('layouts.app')
@section('title', $page['judul'])
@section('content')

@php
$icons = [
    'monitor'        => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17H3a2 2 0 01-2-2V5a2 2 0 012-2h16a2 2 0 012 2v10a2 2 0 01-2 2h-2"/>',
    'shield-check'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>',
    'eye'            => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>',
    'clipboard-list' => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7l2 2 4-4"/>',
];
$colors = [
    'blue'   => ['bg'=>'bg-blue-50',   'icon'=>'text-blue-600',   'border'=>'border-blue-200',  'badge'=>'bg-blue-100 text-blue-700'],
    'green'  => ['bg'=>'bg-green-50',  'icon'=>'text-green-600',  'border'=>'border-green-200', 'badge'=>'bg-green-100 text-green-700'],
    'purple' => ['bg'=>'bg-purple-50', 'icon'=>'text-purple-600', 'border'=>'border-purple-200','badge'=>'bg-purple-100 text-purple-700'],
    'orange' => ['bg'=>'bg-orange-50', 'icon'=>'text-orange-600', 'border'=>'border-orange-200','badge'=>'bg-orange-100 text-orange-700'],
];
$c       = $colors[$page['color']] ?? $colors['blue'];
$iconSvg = $icons[$page['icon']]   ?? $icons['monitor'];
@endphp

{{-- Hero --}}
<section class="{{ $c['bg'] }} border-b {{ $c['border'] }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <nav class="flex items-center gap-2 text-sm text-gray-400 mb-6">
            <a href="{{ route('home') }}" class="hover:text-gray-600">Beranda</a>
            <span>/</span>
            <span class="text-gray-700">{{ $page['judul'] }}</span>
        </nav>
        <div class="flex items-start gap-5">
            <div class="w-16 h-16 rounded-2xl {{ $c['bg'] }} border {{ $c['border'] }} flex items-center justify-center flex-shrink-0">
                <svg class="w-8 h-8 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    {!! $iconSvg !!}
                </svg>
            </div>
            <div>
                <h1 class="text-3xl md:text-4xl font-extrabold text-gray-900 leading-tight">{{ $page['judul'] }}</h1>
                @if($page['subjudul'])
                <p class="text-gray-500 mt-2 text-lg leading-relaxed">{{ $page['subjudul'] }}</p>
                @endif
            </div>
        </div>
    </div>
</section>

{{-- Konten --}}
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12 space-y-12">

    @if($page['intro'])
    <div class="space-y-4 text-gray-600 leading-relaxed">
        @foreach(explode("\n\n", $page['intro']) as $para)
        @if(trim($para))<p>{{ trim($para) }}</p>@endif
        @endforeach
    </div>
    @endif

    @if(!empty($page['poin']))
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-5">Detail Keunggulan</h2>
        <div class="space-y-4">
            @foreach($page['poin'] as $poin)
            <div class="flex gap-4 p-5 bg-white border border-gray-100 rounded-xl hover:shadow-sm transition-shadow">
                <div class="w-8 h-8 rounded-full {{ $c['bg'] }} flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <div>
                    <h3 class="font-semibold text-gray-900">{{ $poin['judul'] }}</h3>
                    <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $poin['desc'] }}</p>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Grid kategori khusus titik-inspeksi --}}
    @if($page['slug'] === 'titik-inspeksi' && !empty($page['extra']))
    <div>
        <h2 class="text-xl font-bold text-gray-900 mb-2">7 Kategori Pemeriksaan</h2>
        <p class="text-gray-500 text-sm mb-6">Gambaran singkat setiap kategori yang diperiksa.</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($page['extra'] as $kat)
            <div class="bg-white border border-gray-200 rounded-xl p-5 hover:border-primary-300 hover:shadow-sm transition-all">
                <div class="flex items-center gap-3 mb-2">
                    <span class="w-8 h-8 rounded-lg {{ $c['bg'] }} flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 {{ $c['icon'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                    </span>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $kat['nama'] }}</h3>
                        <span class="text-xs font-medium {{ $c['badge'] }} px-2 py-0.5 rounded-full">{{ $kat['jumlah'] }}</span>
                    </div>
                </div>
                <p class="text-xs text-gray-500 leading-relaxed">{{ $kat['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- CTA --}}
    <div class="bg-primary-600 rounded-2xl p-8 text-center text-white">
        <h2 class="text-2xl font-bold mb-2">Siap Merasakan Perbedaannya?</h2>
        <p class="text-primary-100 mb-6">Booking inspeksi sekarang dan buktikan kualitas layanan Smart Otto.</p>
        <a href="{{ route('booking.create') }}"
           class="inline-flex items-center gap-2 bg-white text-primary-700 font-bold px-8 py-3 rounded-xl hover:bg-primary-50 transition-colors">
            🔧 Booking Inspeksi Sekarang
        </a>
    </div>

</div>
@endsection
