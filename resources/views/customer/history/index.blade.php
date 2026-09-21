@extends('layouts.customer')
@section('title', 'Riwayat Inspeksi')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Riwayat Inspeksi</h2>
        <a href="{{ route('booking.create') }}" class="btn-primary btn-sm">+ Booking Baru</a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-2">
        <select name="status" class="form-input text-sm" style="max-width:200px" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Booking::$statusLabels as $k=>$v)
            <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
    </form>

    @forelse($bookings as $booking)
    <div class="card p-5">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-1">
                    <span class="font-mono text-sm font-bold text-primary-600">{{ $booking->booking_code }}</span>
                    <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
                </div>
                <p class="font-semibold text-gray-800">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                <p class="text-sm text-gray-500">{{ $booking->vehicle->plate_number }} · {{ $booking->package->name }}</p>
                <p class="text-xs text-gray-400 mt-1">{{ $booking->booking_date->isoFormat('dddd, D MMMM Y') }} pukul {{ substr($booking->booking_time,0,5) }} WIB</p>
            </div>
            <div class="flex flex-col gap-2 flex-shrink-0">
                <a href="{{ route('customer.history.show', $booking) }}" class="btn-secondary btn-sm text-center">Detail</a>
                @if($booking->status === 'completed' && $booking->inspectionResult?->is_verified)
                <a href="{{ route('customer.history.report', $booking) }}" class="btn-primary btn-sm text-center">📋 Laporan</a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card p-10 text-center">
        <p class="text-gray-500">Belum ada riwayat inspeksi.</p>
        <a href="{{ route('booking.create') }}" class="btn-primary mt-4 inline-flex">Booking Sekarang</a>
    </div>
    @endforelse

    {{ $bookings->links() }}
</div>
@endsection
