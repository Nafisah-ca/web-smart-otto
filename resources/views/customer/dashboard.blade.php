@extends('layouts.customer')
@section('title', 'Dashboard')
@section('content')

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard Saya</h2>
            <p class="text-sm text-gray-500">Selamat datang kembali, {{ auth()->user()->name }}</p>
        </div>
        <a href="{{ route('booking.create') }}" class="btn-primary btn-sm">+ Booking Baru</a>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total Booking',       'value'=>$stats['total'],     'color'=>'blue'],
            ['label'=>'Booking Aktif',       'value'=>$stats['active'],    'color'=>'orange'],
            ['label'=>'Inspeksi Selesai',    'value'=>$stats['completed'], 'color'=>'green'],
            ['label'=>'Tagihan Belum Lunas', 'value'=>$stats['unpaid'],    'color'=>'red'],
        ] as $s)
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-{{ $s['color'] }}-600">{{ $s['value'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- Active Bookings --}}
    @if($activeBookings->count())
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">Booking Aktif & Pembayaran</h3>
        <div class="space-y-3">
            @foreach($activeBookings as $booking)
            <div class="card p-4 hover:shadow-md transition-shadow">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-mono text-sm font-bold text-primary-600">{{ $booking->booking_code }}</span>
                            <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
                            @if($booking->transaction)
                            <span class="badge-{{ $booking->transaction->payment_status_color }} text-xs">
                                Pembayaran: {{ $booking->transaction->payment_status_label }}
                            </span>
                            @endif
                        </div>
                        <p class="text-sm font-medium text-gray-800">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} — {{ $booking->vehicle->plate_number }}</p>
                        <p class="text-xs text-gray-500 mt-0.5">{{ $booking->package->name }} · {{ $booking->booking_date->isoFormat('D MMM Y') }} · {{ substr($booking->booking_time, 0, 5) }} WIB</p>
                        @if($booking->inspector)
                        <p class="text-xs text-gray-400 mt-0.5">Inspektor: {{ $booking->inspector->name }}</p>
                        @endif
                    </div>
                    <div class="flex items-center gap-2 flex-shrink-0">
                        @if($booking->transaction && $booking->transaction->payment_status !== 'paid')
                        <a href="{{ route('customer.transactions.show', $booking) }}" class="btn-primary btn-sm text-xs bg-red-600 hover:bg-red-700">
                            ⚡ Bayar QRIS
                        </a>
                        @endif
                        <a href="{{ route('customer.history.show', $booking) }}" class="btn-secondary btn-sm text-xs">Detail</a>
                    </div>
                </div>

                {{-- Progress Tracker --}}
                <div class="mt-4 pt-3 border-t border-gray-100">
                    @php
                        $steps = ['pending'=>0,'confirmed'=>1,'waiting'=>2,'on_progress'=>3,'completed'=>4];
                        $stepLabels = ['Booking','Dikonfirmasi','Menunggu','Dikerjakan','Selesai'];
                        $currentStep = $steps[$booking->status] ?? 0;
                    @endphp
                    <div class="flex items-center gap-0">
                        @foreach($stepLabels as $i => $label)
                        <div class="flex-1 flex flex-col items-center">
                            <div class="w-6 h-6 rounded-full flex items-center justify-center text-xs font-bold
                                {{ $i <= $currentStep ? 'bg-primary-600 text-white' : 'bg-gray-200 text-gray-400' }}">
                                @if($i < $currentStep)✓@else{{ $i+1 }}@endif
                            </div>
                            <span class="text-xs mt-1 text-center hidden sm:block {{ $i <= $currentStep ? 'text-primary-600 font-medium' : 'text-gray-400' }}">{{ $label }}</span>
                        </div>
                        @if(!$loop->last)
                        <div class="flex-1 h-0.5 {{ $i < $currentStep ? 'bg-primary-600' : 'bg-gray-200' }} -mt-4 sm:-mt-2"></div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="card p-10 text-center">
        <div class="text-5xl mb-4">🔧</div>
        <h3 class="font-semibold text-gray-700 mb-2">Belum ada booking aktif</h3>
        <p class="text-sm text-gray-500 mb-5">Mulai booking inspeksi pertama Anda sekarang!</p>
        <a href="{{ route('booking.create') }}" class="btn-primary">Booking Sekarang</a>
    </div>
    @endif

    {{-- Recent Completed --}}
    @if($recentCompleted->count())
    <div>
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold text-gray-800">Inspeksi Terakhir</h3>
            <a href="{{ route('customer.history') }}" class="text-sm text-primary-600 hover:underline">Lihat semua →</a>
        </div>
        <div class="card overflow-hidden">
            <table class="w-full table-auto">
                <tbody>
                    @foreach($recentCompleted as $b)
                    <tr>
                        <td class="px-4 py-3 text-sm">
                            <p class="font-medium text-gray-800">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</p>
                            <p class="text-xs text-gray-500">{{ $b->package->name }} · {{ $b->booking_date->isoFormat('D MMM Y') }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <span class="badge-green">Selesai</span>
                            @if($b->transaction)
                            <span class="badge-{{ $b->transaction->payment_status_color }} ml-1">
                                {{ $b->transaction->payment_status_label }}
                            </span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('customer.history.show', $b) }}" class="text-sm text-primary-600 hover:underline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
