<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Portal Customer') — Smart Otto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-50">

{{-- Top Nav --}}
<nav class="bg-white border-b border-gray-200 sticky top-0 z-40">
    <div class="max-w-5xl mx-auto px-4 flex items-center justify-between h-14">
        <a href="{{ route('home') }}" class="flex items-center gap-2">
            <div class="w-7 h-7 bg-primary-600 rounded-md flex items-center justify-center">
                <span class="text-white font-bold text-xs">SO</span>
            </div>
            <span class="font-bold text-gray-900">Smart Otto</span>
        </a>
        <div class="flex items-center gap-4">
            <span class="text-sm text-gray-600 hidden sm:block">Halo, <strong>{{ auth()->user()->name }}</strong></span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-sm text-gray-500 hover:text-red-600">Keluar</button>
            </form>
        </div>
    </div>
</nav>

<div class="max-w-5xl mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row gap-6">
        {{-- Sidebar --}}
        <aside class="md:w-56 flex-shrink-0">
            <nav class="card p-3 space-y-1">
                <a href="{{ route('customer.dashboard') }}" class="sidebar-link {{ request()->routeIs('customer.dashboard') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Dashboard
                </a>
                <a href="{{ route('booking.create') }}" class="sidebar-link {{ request()->routeIs('booking.create') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    Booking Baru
                </a>
                <a href="{{ route('customer.history') }}" class="sidebar-link {{ request()->routeIs('customer.history*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Riwayat Inspeksi
                </a>
                <a href="{{ route('customer.vehicles.index') }}" class="sidebar-link {{ request()->routeIs('customer.vehicles*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/></svg>
                    Kendaraan Saya
                </a>
                <a href="{{ route('customer.profile.edit') }}" class="sidebar-link {{ request()->routeIs('customer.profile*') ? 'active' : '' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                    Profil Saya
                </a>
            </nav>
        </aside>

        {{-- Content --}}
        <main class="flex-1 min-w-0">
            @if(session('success'))
            <div role="alert" class="mb-4">
                <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
                    <span>✅ {{ session('success') }}</span>
                    <button data-dismiss class="ml-auto">✕</button>
                </div>
            </div>
            @endif
            @if(session('error'))
            <div role="alert" class="mb-4">
                <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
                    <span>❌ {{ session('error') }}</span>
                    <button data-dismiss class="ml-auto">✕</button>
                </div>
            </div>
            @endif
            @yield('content')
        </main>
    </div>
</div>

@stack('scripts')
</body>
</html>
