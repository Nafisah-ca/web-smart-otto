@extends('layouts.admin')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')
@section('content')
<div class="space-y-6">

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @php
            $cards = [
                ['label'=>'Pending',       'val'=>$bookingStats['pending']??0,     'color'=>'yellow', 'icon'=>'⏳'],
                ['label'=>'Dikonfirmasi',  'val'=>$bookingStats['confirmed']??0,   'color'=>'blue',   'icon'=>'✅'],
                ['label'=>'Dikerjakan',    'val'=>$bookingStats['on_progress']??0, 'color'=>'orange', 'icon'=>'🔧'],
                ['label'=>'Selesai',       'val'=>$bookingStats['completed']??0,   'color'=>'green',  'icon'=>'🎉'],
            ];
        @endphp
        @foreach($cards as $c)
        <div class="card p-5">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wide">{{ $c['label'] }}</p>
                    <p class="text-3xl font-bold text-gray-800 mt-1">{{ $c['val'] }}</p>
                </div>
                <span class="text-3xl">{{ $c['icon'] }}</span>
            </div>
        </div>
        @endforeach
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Revenue Bulan Ini</p>
            <p class="text-2xl font-bold text-primary-600 mt-1">Rp {{ number_format($revenueMonth, 0, ',', '.') }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Customer</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalCustomers }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase tracking-wide">Total Inspektor</p>
            <p class="text-2xl font-bold text-gray-800 mt-1">{{ $totalInspectors }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Pending Bookings --}}
        <div class="card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Booking Menunggu Konfirmasi</h3>
                <a href="{{ route('admin.bookings.index', ['status'=>'pending']) }}" class="text-xs text-primary-600 hover:underline">Lihat semua</a>
            </div>
            @forelse($pendingBookings as $b)
            <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-800">{{ $b->user->name }}</p>
                    <p class="text-xs text-gray-500">{{ $b->vehicle->brand }} {{ $b->vehicle->model }} · {{ $b->booking_date->format('d M Y') }}</p>
                </div>
                <a href="{{ route('admin.bookings.show', $b) }}" class="btn-secondary btn-sm text-xs">Proses</a>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada booking pending 🎉</div>
            @endforelse
        </div>

        {{-- Today's Bookings --}}
        <div class="card overflow-hidden">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h3 class="font-semibold text-gray-800">Jadwal Hari Ini</h3>
                <span class="text-xs text-gray-500">{{ today()->isoFormat('D MMMM Y') }}</span>
            </div>
            @forelse($todayBookings as $b)
            <div class="px-5 py-3 border-b border-gray-50 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-primary-600 w-10">{{ substr($b->booking_time,0,5) }}</span>
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $b->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</p>
                    </div>
                </div>
                <span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span>
            </div>
            @empty
            <div class="px-5 py-8 text-center text-sm text-gray-400">Tidak ada jadwal hari ini</div>
            @endforelse
        </div>
    </div>
</div>
@endsection
