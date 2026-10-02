<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $siteName ?? 'Smart Otto') — Inspeksi Kendaraan</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
    @stack('styles')
</head>
<body class="bg-gray-50">

{{-- NAVBAR --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">
            {{-- Logo --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-sm">SO</span>
                </div>
                <span class="font-bold text-lg text-gray-900">{{ $siteName ?? 'Smart Otto' }}</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Beranda</a>
                <a href="{{ route('layanan') }}" class="text-sm font-medium {{ request()->routeIs('layanan') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Layanan</a>
                <a href="{{ route('blog.index') }}" class="text-sm font-medium {{ request()->routeIs('blog*') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Blog</a>
                <a href="{{ route('booking.create') }}" class="text-sm font-medium {{ request()->routeIs('booking*') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Booking</a>
                <a href="{{ route('tentang') }}" class="text-sm font-medium {{ request()->routeIs('tentang') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Tentang</a>
                <a href="{{ route('kontak') }}" class="text-sm font-medium {{ request()->routeIs('kontak') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Kontak</a>
            </div>

            {{-- Auth Buttons --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    {{-- Ikon profil bulat → dashboard sesuai role --}}
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isInspector() ? route('inspector.dashboard') : route('customer.dashboard')) }}"
                       title="{{ auth()->user()->name }}"
                       class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold text-primary-700 hover:ring-2 hover:ring-primary-400 transition-all flex-shrink-0"
                       style="background-color:#fdecea;">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn-secondary btn-sm">Masuk</a>
                    <a href="{{ route('booking.create') }}" class="btn-primary btn-sm">Booking Sekarang</a>
                @endauth
            </div>

            {{-- Mobile toggle --}}
            <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div id="mobile-menu" class="hidden md:hidden border-t border-gray-200 bg-white px-4 py-3 space-y-1">
        <a href="{{ route('home') }}" class="block py-2 text-sm text-gray-700">Beranda</a>
        <a href="{{ route('layanan') }}" class="block py-2 text-sm text-gray-700">Layanan</a>
        <a href="{{ route('blog.index') }}" class="block py-2 text-sm text-gray-700">Blog</a>
        <a href="{{ route('booking.create') }}" class="block py-2 text-sm text-gray-700">Booking</a>
        <a href="{{ route('tentang') }}" class="block py-2 text-sm text-gray-700">Tentang</a>
        <a href="{{ route('kontak') }}" class="block py-2 text-sm text-gray-700">Kontak</a>
        <div class="pt-2 border-t border-gray-100 flex gap-2">
            @auth
                {{-- Ikon profil bulat → dashboard sesuai role --}}
                <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isInspector() ? route('inspector.dashboard') : route('customer.dashboard')) }}"
                   title="{{ auth()->user()->name }}"
                   class="flex items-center justify-center w-9 h-9 rounded-full text-sm font-bold text-primary-700 hover:ring-2 hover:ring-primary-400 transition-all flex-shrink-0"
                   style="background-color:#fdecea;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </a>
            @else
                <a href="{{ route('login') }}" class="btn-secondary btn-sm flex-1 justify-center">Masuk</a>
                <a href="{{ route('booking.create') }}" class="btn-primary btn-sm flex-1 justify-center">Booking</a>
            @endauth
        </div>
    </div>
</nav>

{{-- Flash Messages --}}
@if(session('success'))
<div role="alert" class="max-w-7xl mx-auto px-4 mt-4">
    <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        <span>{{ session('success') }}</span>
        <button data-dismiss class="ml-auto text-green-600 hover:text-green-800">✕</button>
    </div>
</div>
@endif
@if(session('error'))
<div role="alert" class="max-w-7xl mx-auto px-4 mt-4">
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
        <svg class="w-5 h-5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/></svg>
        <span>{{ session('error') }}</span>
        <button data-dismiss class="ml-auto text-red-600 hover:text-red-800">✕</button>
    </div>
</div>
@endif

{{-- Main Content --}}
<main>
    @yield('content')
</main>

{{-- FOOTER --}}
<footer class="bg-navy-950 text-gray-400" style="margin-top:0;">

    {{-- Top accent bar --}}
    <div class="h-px bg-gradient-to-r from-transparent via-primary-600 to-transparent opacity-60"></div>

    {{-- Pre-footer CTA strip --}}
    <div class="bg-navy-900 border-b border-navy-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            <div>
                <p class="text-white font-bold text-xl tracking-tight">Siap menginspeksi kendaraan Anda?</p>
                <p class="text-navy-300 text-sm mt-1">Booking sekarang dan dapatkan laporan inspeksi profesional.</p>
            </div>
            <a href="{{ route('booking.create') }}"
               class="inline-flex items-center justify-center px-7 py-3 bg-primary-600 hover:bg-primary-700 text-white text-sm font-semibold rounded-xl transition-colors duration-150 whitespace-nowrap flex-shrink-0">
                Booking Inspeksi
            </a>
        </div>
    </div>

    {{-- Main footer body --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-14 pb-10">

        {{-- Maps mini strip — hanya tampil jika embed tersedia --}}
        @if($siteMapsEmbed ?? '')
        <div class="mb-10 rounded-2xl overflow-hidden border border-navy-800 shadow-inner">
            <iframe
                src="{{ $siteMapsEmbed }}"
                class="w-full h-40"
                style="border:0; filter: grayscale(30%) brightness(0.85);"
                allowfullscreen
                loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"
                title="Lokasi Smart Otto">
            </iframe>
        </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-12 gap-10 lg:gap-16">

            {{-- Brand column --}}
            <div class="md:col-span-5">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-3 mb-5">
                    <div class="w-9 h-9 bg-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                        <span class="text-white font-bold text-sm tracking-tight">SO</span>
                    </div>
                    <span class="font-bold text-lg text-white tracking-tight">{{ $siteName ?? 'Smart Otto' }}</span>
                </a>
                <p class="text-navy-300 text-sm leading-relaxed max-w-xs">
                    {{ $footerTagline ?: ($siteTagline ?? 'Inspeksi Kendaraan Profesional & Terpercaya') }}
                </p>

                {{-- Contact details --}}
                @php
                    $footerRawPhone = $sitePhone ?? '';
                    $footerWaNum   = preg_replace('/\D/', '', $footerRawPhone);
                    if (str_starts_with($footerWaNum, '0')) {
                        $footerWaNum = '62' . substr($footerWaNum, 1);
                    }
                    $footerWaLink   = $footerWaNum ? 'https://wa.me/' . $footerWaNum : null;
                    $footerRawEmail = $siteEmail ?? '';
                    $footerGmailLink = $footerRawEmail ? 'https://mail.google.com/mail/?view=cm&to=' . rawurlencode($footerRawEmail) : null;
                @endphp

                <div class="mt-6 space-y-3 text-sm">
                    @if($footerRawPhone)
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 flex-shrink-0 mt-px">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                        </span>
                        @if($footerWaLink)
                        <a href="{{ $footerWaLink }}" target="_blank" rel="noopener"
                           class="text-navy-200 hover:text-white transition-colors duration-150">{{ $footerRawPhone }}</a>
                        @else
                        <span class="text-navy-200">{{ $footerRawPhone }}</span>
                        @endif
                    </div>
                    @endif
                    @if($footerRawEmail)
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 flex-shrink-0 mt-px">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </span>
                        <a href="{{ $footerGmailLink }}" target="_blank" rel="noopener"
                           class="text-navy-200 hover:text-white transition-colors duration-150 break-all">{{ $footerRawEmail }}</a>
                    </div>
                    @endif
                    @if($siteAddress ?? '')
                    <div class="flex items-start gap-3">
                        <span class="w-5 h-5 flex-shrink-0 mt-px">
                            <svg class="w-4 h-4 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75"
                                      d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </span>
                        <span class="text-navy-200 whitespace-pre-line">{{ $siteAddress }}</span>
                    </div>
                    @endif
                </div>

                {{-- Quick action buttons --}}
                <div class="flex flex-wrap gap-2 mt-6">
                    @if($footerWaLink)
                    <a href="{{ $footerWaLink }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-navy-700 text-xs font-medium text-navy-300 hover:text-white hover:border-primary-600 hover:bg-primary-600 transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Chat WhatsApp
                    </a>
                    @endif
                    @if($footerGmailLink)
                    <a href="{{ $footerGmailLink }}" target="_blank" rel="noopener"
                       class="inline-flex items-center gap-2 px-4 py-2 rounded-lg border border-navy-700 text-xs font-medium text-navy-300 hover:text-white hover:border-primary-600 hover:bg-primary-600 transition-all duration-150">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        Kirim Email
                    </a>
                    @endif
                </div>

                {{-- Social media --}}
                @if(($footerSocialIg ?? '') || ($footerSocialFb ?? '') || ($footerSocialWa ?? ''))
                <div class="flex items-center gap-2 mt-5">
                    @if($footerSocialIg ?? '')
                    <a href="{{ $footerSocialIg }}" target="_blank" rel="noopener"
                       aria-label="Instagram Smart Otto"
                       class="w-9 h-9 rounded-lg border border-navy-700 flex items-center justify-center text-navy-400 hover:text-white hover:border-primary-600 hover:bg-primary-600 transition-all duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    @endif
                    @if($footerSocialFb ?? '')
                    <a href="{{ $footerSocialFb }}" target="_blank" rel="noopener"
                       aria-label="Facebook Smart Otto"
                       class="w-9 h-9 rounded-lg border border-navy-700 flex items-center justify-center text-navy-400 hover:text-white hover:border-primary-600 hover:bg-primary-600 transition-all duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                        </svg>
                    </a>
                    @endif
                    @if($footerSocialWa ?? '')
                    <a href="{{ $footerSocialWa }}" target="_blank" rel="noopener"
                       aria-label="WhatsApp Smart Otto"
                       class="w-9 h-9 rounded-lg border border-navy-700 flex items-center justify-center text-navy-400 hover:text-white hover:border-primary-600 hover:bg-primary-600 transition-all duration-150">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </a>
                    @endif
                </div>
                @endif
            </div>

            {{-- Spacer on large screens --}}
            <div class="hidden md:block md:col-span-1"></div>

            {{-- Layanan column --}}
            <div class="md:col-span-3">
                <p class="text-xs font-semibold text-primary-500 tracking-widest uppercase mb-5">Layanan</p>
                <ul class="space-y-3 text-sm">
                    <li><a href="{{ route('layanan') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Paket Inspeksi</a></li>
                    <li><a href="{{ route('booking.create') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Booking Inspeksi</a></li>
                    <li><a href="{{ route('blog.index') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Blog</a></li>
                    <li><a href="{{ route('tentang') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Tentang Kami</a></li>
                    <li><a href="{{ route('kontak') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Kontak</a></li>
                </ul>
            </div>

            {{-- Akun column --}}
            <div class="md:col-span-3">
                <p class="text-xs font-semibold text-primary-500 tracking-widest uppercase mb-5">Akun</p>
                <ul class="space-y-3 text-sm">
                    @auth
                        <li><a href="{{ route('customer.dashboard') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Dashboard</a></li>
                        <li><a href="{{ route('customer.history') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Riwayat Inspeksi</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Daftar Akun</a></li>
                    @endauth
                    <li><a href="{{ route('booking.create') }}" class="text-navy-300 hover:text-white transition-colors duration-150">Booking Inspeksi</a></li>
                </ul>
            </div>

        </div>

        {{-- Bottom bar --}}
        <div class="mt-14 pt-6 border-t border-navy-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <p class="text-xs text-navy-500">
                {{ $footerCopyright ?: '© ' . date('Y') . ' ' . ($siteName ?? 'Smart Otto') . '. Seluruh hak cipta dilindungi.' }}
            </p>
            <p class="text-xs text-navy-600">Inspeksi Kendaraan Profesional</p>
        </div>
    </div>
</footer>

@stack('scripts')
@stack('scripts')
</body>
</html>
