@extends('layouts.app')
@section('title', 'Kontak Kami')

@section('content')

{{-- Hero Section --}}
<section class="relative bg-navy-900 overflow-hidden">
    {{-- Subtle geometric accent --}}
    <div class="absolute inset-0 pointer-events-none">
        <div class="absolute top-0 right-0 w-96 h-96 bg-primary-600 opacity-10 rounded-full translate-x-48 -translate-y-24"></div>
        <div class="absolute bottom-0 left-0 w-64 h-64 bg-navy-700 opacity-40 rounded-full -translate-x-20 translate-y-20"></div>
    </div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-28">
        <div class="max-w-2xl">
            <p class="text-primary-400 text-sm font-semibold tracking-widest uppercase mb-4">Hubungi Kami</p>
            <h1 class="text-4xl lg:text-5xl font-bold text-white leading-tight mb-6">
                Ada yang bisa kami<br>bantu untuk Anda?
            </h1>
            <p class="text-navy-200 text-lg leading-relaxed">
                Tim Smart Otto siap membantu Anda menemukan solusi inspeksi kendaraan terbaik. Hubungi kami melalui saluran di bawah ini.
            </p>
        </div>
    </div>
</section>

{{-- Divider accent --}}
<div class="h-1 bg-gradient-to-r from-primary-600 via-primary-500 to-primary-700"></div>

{{-- Contact Content --}}
<section class="bg-white py-20 lg:py-28">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-5 gap-12 lg:gap-16 items-start">

            {{-- Left: Contact Info --}}
            <div class="lg:col-span-2 space-y-2">
                <h2 class="text-2xl font-bold text-navy-900 mb-2">Informasi Kontak</h2>
                <p class="text-gray-500 text-sm leading-relaxed mb-8">
                    Kami tersedia selama jam operasional untuk menjawab pertanyaan dan membantu proses pemesanan Anda.
                </p>

                {{-- Phone / WhatsApp --}}
                @php
                    $rawPhone = $cms['site_phone'] ?? '';
                    // Buat nomor WA: hilangkan karakter non-digit, ganti awalan 0 dengan 62
                    $waNumber = preg_replace('/\D/', '', $rawPhone);
                    if (str_starts_with($waNumber, '0')) {
                        $waNumber = '62' . substr($waNumber, 1);
                    }
                    $waLink = $waNumber ? 'https://wa.me/' . $waNumber : null;
                @endphp
                <div class="group border border-gray-100 rounded-2xl p-6 hover:border-primary-200 hover:shadow-md transition-all duration-200 bg-gray-50 hover:bg-white">
                    <p class="text-xs font-semibold text-primary-600 tracking-widest uppercase mb-2">WhatsApp</p>
                    @if($waLink)
                    <a href="{{ $waLink }}" target="_blank" rel="noopener"
                       class="text-navy-900 font-semibold text-base leading-relaxed hover:text-primary-600 transition-colors duration-150">
                        {{ $rawPhone }}
                    </a>
                    <p class="text-xs text-gray-400 mt-1">Ketuk untuk langsung chat di WhatsApp</p>
                    @else
                    <p class="text-navy-900 font-semibold text-base leading-relaxed">-</p>
                    @endif
                </div>

                {{-- Email --}}
                @php
                    $rawEmail  = $cms['site_email'] ?? '';
                    $gmailLink = $rawEmail ? 'https://mail.google.com/mail/?view=cm&to=' . rawurlencode($rawEmail) : null;
                @endphp
                <div class="group border border-gray-100 rounded-2xl p-6 hover:border-primary-200 hover:shadow-md transition-all duration-200 bg-gray-50 hover:bg-white">
                    <p class="text-xs font-semibold text-primary-600 tracking-widest uppercase mb-2">Email</p>
                    @if($gmailLink)
                    <a href="{{ $gmailLink }}" target="_blank" rel="noopener"
                       class="text-navy-900 font-semibold text-base leading-relaxed break-all hover:text-primary-600 transition-colors duration-150">
                        {{ $rawEmail }}
                    </a>
                    <p class="text-xs text-gray-400 mt-1">Klik untuk kirim pesan via Gmail</p>
                    @else
                    <p class="text-navy-900 font-semibold text-base leading-relaxed">-</p>
                    @endif
                </div>

                {{-- Address --}}
                <div class="group border border-gray-100 rounded-2xl p-6 hover:border-primary-200 hover:shadow-md transition-all duration-200 bg-gray-50 hover:bg-white">
                    <p class="text-xs font-semibold text-primary-600 tracking-widest uppercase mb-2">Alamat</p>
                    <p class="text-navy-900 font-semibold text-base leading-relaxed whitespace-pre-line">{{ $cms['site_address'] ?? '-' }}</p>
                </div>

                {{-- Operating Hours --}}
                <div class="group border border-gray-100 rounded-2xl p-6 hover:border-primary-200 hover:shadow-md transition-all duration-200 bg-gray-50 hover:bg-white">
                    <p class="text-xs font-semibold text-primary-600 tracking-widest uppercase mb-3">Jam Operasional</p>
                    <div class="space-y-2 text-sm">
                        @if(!empty($cms['ops_weekday']))
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Senin – Jumat</span>
                            <span class="text-navy-900 font-semibold">{{ $cms['ops_weekday'] }}</span>
                        </div>
                        @endif
                        @if(!empty($cms['ops_saturday']))
                        <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                            <span class="text-gray-500">Sabtu</span>
                            <span class="text-navy-900 font-semibold">{{ $cms['ops_saturday'] }}</span>
                        </div>
                        @endif
                        @if(!empty($cms['ops_sunday']))
                        <div class="flex justify-between items-center border-t border-gray-100 pt-2">
                            <span class="text-gray-500">Minggu</span>
                            <span class="text-navy-900 font-semibold">{{ $cms['ops_sunday'] }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Right: Map --}}
            <div class="lg:col-span-3">
                <div class="sticky top-24">
                    <h2 class="text-2xl font-bold text-navy-900 mb-2">Lokasi Kami</h2>
                    <p class="text-gray-500 text-sm mb-6">Temukan kami di peta untuk mempermudah kunjungan Anda.</p>

                    @if(!empty($cms['site_maps_embed']))
                    <div class="rounded-2xl overflow-hidden border border-gray-100 shadow-lg">
                        <iframe
                            src="{{ $cms['site_maps_embed'] }}"
                            class="w-full h-96 lg:h-[480px]"
                            style="border:0;"
                            allowfullscreen
                            loading="lazy"
                            referrerpolicy="no-referrer-when-downgrade"
                            title="Lokasi Smart Otto">
                        </iframe>
                    </div>
                    @else
                    <div class="rounded-2xl border border-gray-100 bg-gray-50 h-96 flex items-center justify-center">
                        <div class="text-center">
                            <div class="w-12 h-12 bg-gray-200 rounded-full mx-auto mb-3 flex items-center justify-center">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <p class="text-gray-400 text-sm">Peta belum dikonfigurasi</p>
                        </div>
                    </div>
                    @endif

                    {{-- CTA Card --}}
                    <div class="mt-6 bg-navy-900 rounded-2xl p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                        <div>
                            <p class="text-white font-semibold text-base">Siap untuk inspeksi kendaraan?</p>
                            <p class="text-navy-300 text-sm mt-0.5">Pesan jadwal sekarang, prosesnya cepat dan mudah.</p>
                        </div>
                        <a href="{{ route('booking.create') }}"
                           class="inline-flex items-center justify-center px-6 py-2.5 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors duration-150 whitespace-nowrap flex-shrink-0">
                            Booking Sekarang
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

@endsection
