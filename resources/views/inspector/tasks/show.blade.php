@extends('layouts.inspector')
@section('title', 'Detail Tugas')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <a href="{{ route('inspector.tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
        <div class="flex gap-2">
            @if(in_array($booking->status, ['confirmed','waiting','on_progress']))
            <a href="{{ route('inspector.tasks.form', $booking) }}" class="btn-primary btn-sm">🔧 {{ $booking->inspectionResult ? 'Edit Hasil' : 'Isi Hasil Inspeksi' }}</a>
            @endif
            @if($booking->inspectionResult && !$booking->inspectionResult->is_verified && $booking->status === 'on_progress')
            <form method="POST" action="{{ route('inspector.tasks.verify', $booking) }}">
                @csrf
                <button class="btn-primary btn-sm bg-green-600 hover:bg-green-700">✅ Verifikasi & Selesai</button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card p-5 space-y-2">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold">Info Booking</h3>
                <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
            </div>
            <p class="font-mono text-sm text-primary-600 font-bold">{{ $booking->booking_code }}</p>
            <p class="text-sm"><span class="text-gray-500">Customer:</span> {{ $booking->user->name }}</p>
            <p class="text-sm"><span class="text-gray-500">Telepon:</span> {{ $booking->user->phone }}</p>
            <p class="text-sm"><span class="text-gray-500">Tanggal:</span> {{ $booking->booking_date->isoFormat('D MMM Y') }} · {{ substr($booking->booking_time,0,5) }}</p>
            <p class="text-sm"><span class="text-gray-500">Paket:</span> {{ $booking->package->name }}</p>
            @if($booking->notes)
            <p class="text-sm bg-amber-50 border border-amber-100 rounded p-2"><span class="text-gray-500">Catatan:</span> {{ $booking->notes }}</p>
            @endif
        </div>
        <div class="card p-5 space-y-2">
            <h3 class="font-semibold">Data Kendaraan</h3>
            <p class="text-sm"><span class="text-gray-500">Kendaraan:</span> {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
            <p class="text-sm"><span class="text-gray-500">Plat:</span> {{ $booking->vehicle->plate_number }}</p>
            <p class="text-sm"><span class="text-gray-500">Jenis:</span> {{ ucfirst($booking->vehicle->type) }}</p>
            <p class="text-sm"><span class="text-gray-500">Warna:</span> {{ $booking->vehicle->color ?? '-' }}</p>
        </div>
    </div>

    @if($booking->inspectionResult)
    @php $r = $booking->inspectionResult; @endphp
    <div class="card p-5">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold">Hasil Inspeksi</h3>
            @if($r->is_verified)
            <span class="badge-green">✅ Terverifikasi</span>
            @else
            <span class="badge-yellow">Belum Diverifikasi</span>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
            <div>
                <p class="text-gray-500">Kondisi</p>
                <span class="badge-{{ $r->condition_color }}">{{ $r->condition_label }}</span>
            </div>
            <div>
                <p class="text-gray-500">Selesai</p>
                <p>{{ $r->completed_at?->isoFormat('D MMM Y, HH:mm') ?? '-' }}</p>
            </div>
        </div>
        @if($r->recommendation)
        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $r->recommendation }}</p>
        @endif
    </div>
    @endif

    @if($booking->transaction)
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Transaksi</h3>
            <a href="{{ route('transaction.show', $booking) }}" class="btn-secondary btn-sm text-xs">Kelola</a>
        </div>
        <p class="text-sm">Total: <strong>{{ $booking->transaction->formatted_total }}</strong> — <span class="badge-{{ $booking->transaction->payment_status === 'paid' ? 'green' : 'red' }}">{{ $booking->transaction->payment_status_label }}</span></p>
    </div>
    @elseif($booking->status === 'on_progress')
    <div class="card p-4">
        <p class="text-sm text-gray-600 mb-2">Belum ada transaksi.</p>
        <a href="{{ route('transaction.show', $booking) }}" class="btn-primary btn-sm">Buat Transaksi</a>
    </div>
    @endif
</div>
@endsection
