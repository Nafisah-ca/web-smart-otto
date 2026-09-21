<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Smart Otto</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-gray-100 h-screen flex overflow-hidden">

{{-- Sidebar --}}
<aside class="w-64 bg-white border-r border-gray-200 flex flex-col flex-shrink-0 h-full">
    <div class="h-16 flex items-center px-5 border-b border-gray-200">
        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                <span class="text-white font-bold text-sm">SO</span>
            </div>
            <div>
                <p class="font-bold text-sm text-gray-900">Smart Otto</p>
                <p class="text-xs text-gray-500">Admin Panel</p>
            </div>
        </a>
    </div>

    <nav class="flex-1 overflow-y-auto px-3 py-4 space-y-1">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7h18M3 12h18M3 17h18"/></svg>
            Dashboard
        </a>

        <p class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Operasional</p>
        <a href="{{ route('admin.bookings.index') }}" class="sidebar-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Booking
        </a>
        <a href="{{ route('admin.customers.index') }}" class="sidebar-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
            Customer
        </a>
        <a href="{{ route('admin.inspectors.index') }}" class="sidebar-link {{ request()->routeIs('admin.inspectors*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
            Inspektor
        </a>
        <a href="{{ route('admin.reports.index') }}" class="sidebar-link {{ request()->routeIs('admin.reports*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
            Laporan
        </a>

        <p class="px-3 pt-3 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Master Data</p>
        <a href="{{ route('admin.packages.index') }}" class="sidebar-link {{ request()->routeIs('admin.packages*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
            Paket Inspeksi
        </a>
        <a href="{{ route('admin.checklist-items.index') }}" class="sidebar-link {{ request()->routeIs('admin.checklist-items*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Checklist Item
        </a>
        <a href="{{ route('admin.tariffs.index') }}" class="sidebar-link {{ request()->routeIs('admin.tariffs*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            Master Tarif
        </a>
        <a href="{{ route('admin.cms.index') }}" class="sidebar-link {{ request()->routeIs('admin.cms*') ? 'active' : '' }}">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            CMS Konten
        </a>
    </nav>

    <div class="p-3 border-t border-gray-200">
        <div class="flex items-center gap-3 px-2 py-2">
            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-800 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500">Admin</p>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}" class="mt-1">
            @csrf
            <button type="submit" class="w-full text-left sidebar-link text-red-600 hover:bg-red-50">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Keluar
            </button>
        </form>
    </div>
</aside>

{{-- Main --}}
<div class="flex-1 flex flex-col overflow-hidden">
    {{-- Top Bar --}}
    <header class="h-16 bg-white border-b border-gray-200 flex items-center px-6 flex-shrink-0">
        <h1 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h1>
        <div class="ml-auto flex items-center gap-2 text-sm text-gray-500">
            <span>{{ now()->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
    </header>

    {{-- Alerts --}}
    @if(session('success'))
    <div class="mx-6 mt-4" role="alert">
        <div class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 rounded-lg px-4 py-3 text-sm">
            <span>✅ {{ session('success') }}</span>
            <button data-dismiss class="ml-auto">✕</button>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-6 mt-4" role="alert">
        <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-800 rounded-lg px-4 py-3 text-sm">
            <span>❌ {{ session('error') }}</span>
            <button data-dismiss class="ml-auto">✕</button>
        </div>
    </div>
    @endif

    {{-- Content --}}
    <main class="flex-1 overflow-y-auto p-6">
        @yield('content')
    </main>
</div>

@stack('scripts')
</body>
</html>
