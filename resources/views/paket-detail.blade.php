@extends('layouts.app')
@section('title', $package->name)
@section('content')

<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-12">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500 mb-6">
        <a href="{{ route('home') }}" class="hover:text-gray-700">Beranda</a>
        <span>/</span>
        <a href="{{ route('layanan') }}" class="hover:text-gray-700">Layanan</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $package->name }}</span>
    </nav>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        {{-- Info Utama --}}
        <div class="md:col-span-2 space-y-6">
            <div class="card p-8">
                {{-- Icon & Nama --}}
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 bg-primary-50 rounded-2xl flex items-center justify-center flex-shrink-0 border border-primary-100">
                        <svg class="w-8 h-8 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $package->name }}</h1>
                        <p class="text-primary-600 font-bold text-xl mt-1">{{ $package->formatted_price }}</p>
                        <div class="flex items-center gap-1.5 text-sm text-gray-500 mt-1">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Estimasi {{ $package->duration_estimate }} menit
                        </div>
                    </div>
                </div>

                {{-- Deskripsi --}}
                <p class="text-gray-600 leading-relaxed">{{ $package->description }}</p>
            </div>

            {{-- Checklist --}}
            @if($package->checklistItems->count())
            <div class="card p-8">
                <h2 class="text-lg font-bold text-gray-900 mb-5">Yang Diperiksa</h2>
                @php $grouped = $package->checklistItems->groupBy('category'); @endphp
                <div class="space-y-5">
                    @foreach($grouped as $cat => $items)
                    <div>
                        <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-2">{{ $cat }}</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-1.5">
                            @foreach($items as $item)
                            <div class="flex items-center gap-2 text-sm text-gray-700">
                                <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ $item->item_name }}
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- Sidebar CTA --}}
        <div class="space-y-4">
            <div class="card p-6 sticky top-24">
                <div class="text-center mb-5">
                    <p class="text-2xl font-bold text-primary-600">{{ $package->formatted_price }}</p>
                    <p class="text-sm text-gray-500 mt-1">Estimasi {{ $package->duration_estimate }} menit</p>
                </div>

                @auth
                    @if(auth()->user()->isCustomer())
                    <a href="{{ route('booking.create', ['package' => $package->id]) }}"
                       class="btn-primary w-full justify-center text-base py-3">
                        Booking Paket Ini
                    </a>
                    @else
                    <a href="{{ route('booking.create', ['package' => $package->id]) }}"
                       class="btn-primary w-full justify-center text-base py-3">
                        Booking Sekarang
                    </a>
                    @endif
                @else
                <a href="{{ route('login') }}"
                   class="btn-primary w-full justify-center text-base py-3">
                    Masuk untuk Booking
                </a>
                <p class="text-xs text-center text-gray-400 mt-2">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary-600 hover:underline">Daftar gratis</a>
                </p>
                @endauth

                <div class="mt-4 pt-4 border-t border-gray-100 space-y-2 text-sm text-gray-600">
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Laporan digital lengkap
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Teknisi bersertifikat
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        Reschedule mudah
                    </div>
                </div>
            </div>

            <a href="{{ route('layanan') }}" class="btn-secondary w-full justify-center text-sm">
                Lihat Semua Paket
            </a>
        </div>
    </div>
</div>

@endsection
