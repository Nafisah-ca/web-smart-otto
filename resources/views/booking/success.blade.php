@extends('layouts.app')
@section('title', 'Booking Berhasil')
@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Berhasil Dibuat!</h1>
    @if(!$booking->transaction || $booking->transaction->payment_status === 'price_pending')
    <p class="text-gray-500 mb-6">Booking Anda telah kami catat. Mohon menunggu konfirmasi admin untuk penetapan harga fiks sebelum melakukan pembayaran.</p>
    @else
    <p class="text-gray-500 mb-6">Booking Anda telah kami catat. Silakan selesaikan pembayaran untuk memprioritaskan jadwal inspeksi.</p>
    @endif

    <div class="card p-6 text-left space-y-3 mb-6 shadow-sm">
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Kode Booking</span>
            <span class="font-bold text-primary-600 font-mono">{{ $booking->booking_code }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Kendaraan</span>
            <span class="font-medium">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Nomor Polisi</span>
            <span class="font-medium">{{ $booking->vehicle->plate_number }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Paket / Kategori</span>
            <span class="font-medium">{{ $booking->package?->name ?? $booking->vehicleCategory?->name ?? 'Inspeksi Kendaraan' }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tanggal & Jam</span>
            <span class="font-medium">{{ $booking->booking_date->isoFormat('dddd, D MMMM Y') }} · {{ substr($booking->booking_time, 0, 5) }} WIB</span>
        </div>
        @if($booking->transaction)
        <div class="flex justify-between text-sm pt-2 border-t border-gray-100 items-center">
            <span class="text-gray-700 font-semibold">Total Tagihan</span>
            @if($booking->transaction->payment_status === 'price_pending')
            <span class="font-semibold text-amber-600 text-sm">Menunggu Konfirmasi Admin / Harga Fiks</span>
            @else
            <span class="font-extrabold text-primary-600 text-base">{{ $booking->transaction->formatted_total }}</span>
            @endif
        </div>
        @endif
        <div class="flex justify-between text-sm pt-2 border-t border-gray-100">
            <span class="text-gray-500">Status Booking</span>
            <span class="badge-yellow">Menunggu Konfirmasi</span>
        </div>
    </div>

    @if(!$booking->transaction || $booking->transaction->payment_status === 'price_pending')
    {{-- Banner Menunggu Konfirmasi Admin / Menunggu Harga Fiks --}}
    <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 text-left mb-6 flex items-center gap-3.5 shadow-sm">
        <div class="w-10 h-10 rounded-full bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0 text-xl font-bold">
            ⏳
        </div>
        <div>
            <p class="font-bold text-amber-900 text-sm">Menunggu Konfirmasi Admin / Menunggu Harga Fiks</p>
            <p class="text-xs text-amber-700 mt-0.5">Admin kami sedang meninjau detail booking Anda. Pembayaran akan dapat dilakukan setelah harga fiks ditentukan oleh admin.</p>
        </div>
    </div>

    <div class="flex flex-wrap gap-3 justify-center">
        <a href="{{ route('customer.dashboard') }}" class="btn-primary">
            Lihat Dashboard
        </a>
        <a href="{{ route('customer.history.show', $booking) }}" class="btn-secondary">
            Detail Booking
        </a>
    </div>
    @else
    {{-- Banner Pembayaran QRIS / All Payment (Jika sudah ada harga fiks) --}}
    <div class="bg-gradient-to-r from-red-50 to-orange-50 border border-red-200 rounded-xl p-4 text-left mb-6 flex items-center justify-between gap-4">
        <div>
            <p class="font-bold text-gray-900 text-sm">⚡ Bayar Cepat via QRIS / Bank Transfer</p>
            <p class="text-xs text-gray-600 mt-0.5">Tersedia QRIS (GoPay, OVO, DANA, ShopeePay, BCA, Mandiri, BRI, BNI).</p>
        </div>
        <a href="{{ route('customer.transactions.show', $booking) }}" class="btn-primary btn-sm flex-shrink-0">
            Bayar Sekarang →
        </a>
    </div>

    <div class="flex flex-wrap gap-3 justify-center">
        <a href="{{ route('customer.transactions.show', $booking) }}" class="btn-primary">
            💳 Tampilkan QRIS & Pembayaran
        </a>
        <a href="{{ route('customer.dashboard') }}" class="btn-secondary">
            Lihat Dashboard
        </a>
    </div>
    @endif
</div>
@endsection
