@extends('layouts.customer')
@section('title', 'Detail Inspeksi')
@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('customer.history') }}" class="text-gray-400 hover:text-gray-600">← Kembali</a>
        <h2 class="text-xl font-bold text-gray-900">Detail Booking</h2>
    </div>

    {{-- Info Booking --}}
    <div class="card p-5">
        <div class="flex items-start justify-between mb-4">
            <div>
                <span class="font-mono text-lg font-bold text-primary-600">{{ $booking->booking_code }}</span>
                <span class="badge-{{ $booking->status_color }} ml-2">{{ $booking->status_label }}</span>
            </div>
            @if($booking->status === 'completed' && $booking->inspectionResult?->is_verified)
            <a href="{{ route('customer.history.report', $booking) }}" class="btn-primary btn-sm">📋 Lihat Laporan</a>
            @endif
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Kendaraan</p>
                <p class="font-medium">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} {{ $booking->vehicle->year }}</p>
            </div>
            <div>
                <p class="text-gray-500">Nomor Polisi</p>
                <p class="font-medium">{{ $booking->vehicle->plate_number }}</p>
            </div>
            <div>
                <p class="text-gray-500">Paket</p>
                <p class="font-medium">{{ $booking->package->name }}</p>
            </div>
            <div>
                <p class="text-gray-500">Biaya Inspeksi</p>
                <p class="font-medium">{{ $booking->package->formatted_price }}</p>
            </div>
            <div>
                <p class="text-gray-500">Tanggal & Jam</p>
                <p class="font-medium">{{ $booking->booking_date->isoFormat('D MMMM Y') }} · {{ substr($booking->booking_time,0,5) }} WIB</p>
            </div>
            <div>
                <p class="text-gray-500">Inspektor</p>
                <p class="font-medium">{{ $booking->inspector?->name ?? 'Belum di-assign' }}</p>
            </div>
            @if($booking->notes)
            <div class="sm:col-span-2">
                <p class="text-gray-500">Catatan</p>
                <p class="font-medium">{{ $booking->notes }}</p>
            </div>
            @endif
        </div>
    </div>

    {{-- Progress Tracker --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Progress Layanan</h3>
        @php
            $steps = [
                ['key'=>'pending','label'=>'Booking Dibuat','icon'=>'📝'],
                ['key'=>'confirmed','label'=>'Dikonfirmasi','icon'=>'✅'],
                ['key'=>'waiting','label'=>'Menunggu Layanan','icon'=>'⏳'],
                ['key'=>'on_progress','label'=>'Sedang Dikerjakan','icon'=>'🔧'],
                ['key'=>'completed','label'=>'Selesai','icon'=>'🎉'],
            ];
            $order = ['pending'=>0,'confirmed'=>1,'waiting'=>2,'on_progress'=>3,'completed'=>4,'cancelled'=>-1];
            $current = $order[$booking->status] ?? 0;
        @endphp
        <ol class="space-y-3">
            @foreach($steps as $i => $step)
            @php $done = $current >= $i; $active = $current === $i; @endphp
            <li class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full flex items-center justify-center text-sm flex-shrink-0
                    {{ $done ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-400' }}">
                    @if($done && !$active)✓@else{{ $step['icon'] }}@endif
                </div>
                <div>
                    <p class="text-sm font-medium {{ $done ? 'text-gray-800' : 'text-gray-400' }}">{{ $step['label'] }}</p>
                    @if($active && $booking->status !== 'completed')
                    <p class="text-xs text-primary-600">Status saat ini</p>
                    @endif
                </div>
            </li>
            @endforeach
        </ol>
        @if($booking->status === 'cancelled')
        <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-3 text-sm text-red-700">
            <strong>Dibatalkan:</strong> {{ $booking->cancellation_reason ?? '-' }}
        </div>
        @endif
    </div>

    {{-- Hasil Inspeksi (jika ada) --}}
    @if($booking->inspectionResult)
    @php $result = $booking->inspectionResult; @endphp
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Hasil Inspeksi</h3>
        @if($result->is_verified)
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500">Kondisi Kendaraan</p>
                <span class="badge-{{ $result->condition_color }} mt-1">{{ $result->condition_label }}</span>
            </div>
            <div>
                <p class="text-gray-500">Diverifikasi</p>
                <p class="font-medium">{{ $result->verified_at?->isoFormat('D MMM Y, HH:mm') }}</p>
            </div>
            @if($result->recommendation)
            <div class="sm:col-span-2">
                <p class="text-gray-500">Rekomendasi</p>
                <p class="font-medium">{{ $result->recommendation }}</p>
            </div>
            @endif
        </div>
        @else
        <p class="text-sm text-gray-500">Hasil inspeksi sedang diproses. Akan tersedia setelah diverifikasi oleh inspektor.</p>
        @endif
    </div>
    @endif

    {{-- Transaksi --}}
    @if($booking->transaction)
    @php $trx = $booking->transaction; @endphp
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-4">Tagihan</h3>
        <div class="space-y-2 text-sm">
            @foreach($trx->items as $item)
            <div class="flex justify-between">
                <span class="text-gray-700">{{ $item->item_name }} ({{ $item->quantity }} {{ $item->unit }})</span>
                <span class="font-medium">{{ $item->formatted_subtotal }}</span>
            </div>
            @endforeach
            <div class="border-t border-gray-100 pt-2 flex justify-between text-xs text-gray-500">
                <span>PPN 11%</span>
                <span>Rp {{ number_format($trx->tax, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-gray-900 text-base border-t border-gray-200 pt-2">
                <span>Total</span>
                <span>{{ $trx->formatted_total }}</span>
            </div>
            <div class="flex justify-between text-sm pt-1">
                <span class="text-gray-500">Status Pembayaran</span>
                <span class="badge-{{ $trx->payment_status === 'paid' ? 'green' : ($trx->payment_status === 'partial' ? 'yellow' : 'red') }}">
                    {{ $trx->payment_status_label }}
                </span>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
