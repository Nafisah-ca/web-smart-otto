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
                <span class="font-bold text-lg text-gray-900">Smart Otto</span>
            </a>

            {{-- Desktop Menu --}}
            <div class="hidden md:flex items-center gap-6">
                <a href="{{ route('home') }}" class="text-sm font-medium {{ request()->routeIs('home') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Beranda</a>
                <a href="{{ route('layanan') }}" class="text-sm font-medium {{ request()->routeIs('layanan') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Layanan</a>
                <a href="{{ route('tentang') }}" class="text-sm font-medium {{ request()->routeIs('tentang') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Tentang</a>
                <a href="{{ route('kontak') }}" class="text-sm font-medium {{ request()->routeIs('kontak') ? 'text-primary-600' : 'text-gray-600 hover:text-gray-900' }}">Kontak</a>
            </div>

            {{-- Auth Buttons --}}
            <div class="hidden md:flex items-center gap-3">
                @auth
                    <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : (auth()->user()->isInspector() ? route('inspector.dashboard') : route('customer.dashboard')) }}"
                       class="text-sm font-medium text-gray-600 hover:text-gray-900">Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-primary btn-sm">Keluar</button>
                    </form>
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
        <a href="{{ route('tentang') }}" class="block py-2 text-sm text-gray-700">Tentang</a>
        <a href="{{ route('kontak') }}" class="block py-2 text-sm text-gray-700">Kontak</a>
        <div class="pt-2 border-t border-gray-100 flex gap-2">
            @auth
                <a href="{{ route('customer.dashboard') }}" class="btn-secondary btn-sm flex-1 justify-center">Dashboard</a>
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
<footer class="bg-gray-900 text-gray-300 mt-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="md:col-span-2">
                <div class="flex items-center gap-2 mb-4">
                    <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                        <span class="text-white font-bold text-sm">SO</span>
                    </div>
                    <span class="font-bold text-lg text-white">Smart Otto</span>
                </div>
                <p class="text-sm text-gray-400 leading-relaxed">{{ $siteTagline ?? 'Inspeksi Kendaraan Profesional & Terpercaya' }}</p>
                <div class="mt-4 space-y-1 text-sm">
                    <p>📞 {{ $sitePhone ?? '' }}</p>
                    <p>✉️ {{ $siteEmail ?? '' }}</p>
                    <p>📍 {{ $siteAddress ?? '' }}</p>
                </div>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Layanan</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="{{ route('layanan') }}" class="hover:text-white">Inspeksi Basic</a></li>
                    <li><a href="{{ route('layanan') }}" class="hover:text-white">Inspeksi Standar</a></li>
                    <li><a href="{{ route('layanan') }}" class="hover:text-white">Inspeksi Premium</a></li>
                </ul>
            </div>
            <div>
                <h4 class="text-white font-semibold mb-3">Akun</h4>
                <ul class="space-y-2 text-sm">
                    @auth
                        <li><a href="{{ route('customer.dashboard') }}" class="hover:text-white">Dashboard</a></li>
                        <li><a href="{{ route('customer.history') }}" class="hover:text-white">Riwayat Inspeksi</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white">Daftar</a></li>
                    @endauth
                    <li><a href="{{ route('booking.create') }}" class="hover:text-white">Booking Inspeksi</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-8 pt-6 text-center text-xs text-gray-500">
            © {{ date('Y') }} Smart Otto. Seluruh hak cipta dilindungi.
        </div>
    </div>
</footer>

@stack('scripts')
</body>
</html>
