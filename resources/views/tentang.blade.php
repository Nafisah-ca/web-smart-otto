@extends('layouts.app')
@section('title', $cms['about_title'] ?: 'Tentang Kami')
@section('content')

{{-- HERO BANNER --}}
<div class="bg-primary-600 py-12">
    <div class="max-w-3xl mx-auto px-6 text-center">
        <h1 class="text-3xl md:text-4xl font-bold text-white tracking-tight">
            {{ $cms['about_title'] ?: 'Tentang Kami' }}
        </h1>
        <p class="text-primary-200 text-sm mt-3">
            <a href="{{ route('home') }}" class="hover:text-white transition-colors">Beranda</a>
            <span class="mx-2 opacity-40">/</span>
            <span>Tentang Kami</span>
        </p>
    </div>
</div>

<div class="max-w-3xl mx-auto px-6 py-12">
    <div class="bg-white border border-gray-200 rounded-xl shadow-sm overflow-hidden">

        {{-- LOGO --}}
        <div class="flex flex-col items-center pt-10 pb-7 border-b border-gray-100">
            @if($cms['about_logo'])
                <img src="{{ asset($cms['about_logo']) }}" alt="{{ $cms['site_name'] }}"
                     class="h-16 object-contain mb-5">
            @else
                <div class="w-16 h-16 bg-primary-600 rounded-xl flex items-center justify-center mb-5 shadow-sm">
                    <span class="text-white font-bold text-xl">SO</span>
                </div>
            @endif
            <h2 class="text-base font-semibold text-gray-700 tracking-wide">{{ $cms['site_name'] ?? 'Smart Otto' }}</h2>
        </div>

        <div class="divide-y divide-gray-100">

            {{-- SAMBUTAN / DESKRIPSI --}}
            @if($cms['about_welcome'] || $cms['about_content'])
            <div class="px-8 py-8">
                @if($cms['about_welcome'])
                <p class="text-lg font-semibold text-gray-900 leading-snug mb-4">
                    {{ $cms['about_welcome'] }}
                </p>
                @endif
                @if($cms['about_content'])
                <div class="text-gray-500 text-sm leading-relaxed space-y-3">
                    @foreach(array_filter(array_map('trim', explode("\n", $cms['about_content']))) as $para)
                    <p>{{ $para }}</p>
                    @endforeach
                </div>
                @endif
            </div>
            @endif

            {{-- VISI --}}
            @if($cms['about_vision'])
            <div class="px-8 py-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1 h-6 bg-primary-500 rounded-full flex-shrink-0"></div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Visi Kami</h3>
                </div>
                <p class="text-gray-500 text-sm leading-relaxed">{{ $cms['about_vision'] }}</p>
            </div>
            @endif

            {{-- MISI --}}
            @if($cms['about_mission'])
            @php $misiLines = array_filter(array_map('trim', explode("\n", $cms['about_mission']))); @endphp
            <div class="px-8 py-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 bg-primary-500 rounded-full flex-shrink-0"></div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Misi Kami</h3>
                </div>
                <ul class="space-y-4">
                    @foreach($misiLines as $line)
                    @php
                        $parts   = explode(':', $line, 2);
                        $hasHead = count($parts) === 2 && strlen(trim($parts[0])) < 60;
                    @endphp
                    <li class="flex gap-3 text-sm text-gray-500 leading-relaxed">
                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-primary-400 flex-shrink-0"></span>
                        <span>
                            @if($hasHead)
                                <span class="font-semibold text-gray-800">{{ trim($parts[0]) }}:</span>{{ $parts[1] }}
                            @else
                                {{ $line }}
                            @endif
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- MENGAPA MEMILIH KAMI --}}
            @if(!empty($cms['keunggulan_items']))
            <div class="px-8 py-8">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-1 h-6 bg-primary-500 rounded-full flex-shrink-0"></div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">
                        {{ $cms['keunggulan_title'] ?: 'Mengapa Memilih Kami?' }}
                    </h3>
                </div>
                <ul class="space-y-4">
                    @foreach($cms['keunggulan_items'] as $item)
                    <li class="flex gap-3 text-sm text-gray-500 leading-relaxed">
                        <span class="mt-1.5 w-1.5 h-1.5 rounded-full bg-primary-400 flex-shrink-0"></span>
                        <span>
                            <span class="font-semibold text-gray-800">{{ $item['title'] }}</span>
                            @if(!empty($item['desc'])): {{ $item['desc'] }}@endif
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{-- HUBUNGI KAMI --}}
            <div class="px-8 py-8">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-1 h-6 bg-primary-500 rounded-full flex-shrink-0"></div>
                    <h3 class="text-xs font-bold text-gray-900 uppercase tracking-wider">Hubungi Kami</h3>
                </div>
                <p class="text-sm text-gray-500 leading-relaxed mb-4">
                    Kami siap membantu Anda memastikan kendaraan dalam kondisi prima.
                    @if($cms['site_phone'])
                        Hubungi kami di <span class="font-medium text-gray-700">{{ $cms['site_phone'] }}</span>
                    @endif
                    @if($cms['site_phone'] && $cms['site_email'])
                        atau
                    @endif
                    @if($cms['site_email'])
                        email ke <span class="font-medium text-gray-700">{{ $cms['site_email'] }}</span>
                    @endif.
                    Untuk informasi lebih lanjut, kunjungi
                    <a href="{{ route('kontak') }}" class="text-primary-600 hover:underline">halaman kontak kami</a>.
                </p>
                <p class="text-sm font-semibold text-gray-800">
                    {{ $cms['site_name'] ?? 'Smart Otto' }} — {{ $cms['site_tagline'] ?? 'Inspeksi Kendaraan Profesional & Terpercaya' }}
                </p>
                <p class="text-sm text-gray-400 mt-2 leading-relaxed">
                    Dengan {{ $cms['site_name'] ?? 'Smart Otto' }}, Anda dapat memastikan kendaraan dalam kondisi prima
                    dengan tenang dan percaya diri. Terima kasih telah mempercayakan inspeksi kendaraan Anda kepada kami.
                </p>
            </div>

        </div>
    </div>
</div>

@endsection
