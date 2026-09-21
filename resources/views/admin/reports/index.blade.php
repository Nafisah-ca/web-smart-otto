@extends('layouts.admin')
@section('page-title', 'Laporan Bulanan')
@section('content')
<div class="space-y-5">
    <form method="GET" class="flex gap-3 items-end">
        <div>
            <label class="form-label">Pilih Bulan</label>
            <input type="month" name="month" value="{{ $month }}" class="form-input">
        </div>
        <button class="btn-primary">Tampilkan</button>
        <a href="{{ route('admin.reports.export', ['month'=>$month]) }}" class="btn-secondary">📥 Export CSV</a>
    </form>

    {{-- Summary --}}
    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase">Total Booking</p>
            <p class="text-3xl font-bold text-gray-800">{{ $bookings->count() }}</p>
        </div>
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase">Revenue</p>
            <p class="text-3xl font-bold text-primary-600">Rp {{ number_format($revenue,0,',','.') }}</p>
        </div>
        @foreach($statusSummary as $status => $count)
        <div class="card p-5">
            <p class="text-xs text-gray-500 uppercase">{{ \App\Models\Booking::$statusLabels[$status] ?? $status }}</p>
            <p class="text-2xl font-bold text-gray-700">{{ $count }}</p>
        </div>
        @endforeach
    </div>

    {{-- Table --}}
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>Kode</th><th>Tanggal</th><th>Customer</th><th>Kendaraan</th><th>Paket</th><th>Inspektor</th><th>Status</th><th>Tagihan</th></tr></thead>
            <tbody>
                @forelse($bookings as $b)
                <tr>
                    <td class="font-mono text-xs text-primary-600">{{ $b->booking_code }}</td>
                    <td class="text-sm">{{ $b->booking_date->format('d/m') }}</td>
                    <td class="text-sm">{{ $b->user->name }}</td>
                    <td class="text-sm">{{ $b->vehicle->plate_number }}</td>
                    <td class="text-sm">{{ $b->package->name }}</td>
                    <td class="text-sm">{{ $b->inspector?->name ?? '-' }}</td>
                    <td><span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    <td class="text-sm font-semibold">{{ $b->transaction?->formatted_total ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-8 text-gray-400">Tidak ada data untuk bulan ini</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
