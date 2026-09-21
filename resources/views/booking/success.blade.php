@extends('layouts.app')
@section('title', 'Booking Berhasil')
@section('content')
<div class="max-w-lg mx-auto px-4 py-16 text-center">
    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
        <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
        </svg>
    </div>
    <h1 class="text-2xl font-bold text-gray-900 mb-2">Booking Berhasil!</h1>
    <p class="text-gray-500 mb-8">Booking Anda telah diterima dan sedang menunggu konfirmasi dari tim Smart Otto.</p>

    <div class="card p-6 text-left space-y-3 mb-8">
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
            <span class="text-gray-500">Paket</span>
            <span class="font-medium">{{ $booking->package->name }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Tanggal</span>
            <span class="font-medium">{{ $booking->booking_date->isoFormat('dddd, D MMMM Y') }}</span>
        </div>
        <div class="flex justify-between text-sm">
            <span class="text-gray-500">Jam</span>
            <span class="font-medium">{{ substr($booking->booking_time, 0, 5) }} WIB</span>
        </div>
        <div class="flex justify-between text-sm pt-2 border-t border-gray-100">
            <span class="text-gray-500">Status</span>
            <span class="badge-yellow">Menunggu Konfirmasi</span>
        </div>
    </div>

    <div class="flex gap-3 justify-center">
        <a href="{{ route('customer.dashboard') }}" class="btn-primary">Lihat Dashboard</a>
        <a href="{{ route('home') }}" class="btn-secondary">Kembali ke Beranda</a>
    </div>
</div>
@endsection
