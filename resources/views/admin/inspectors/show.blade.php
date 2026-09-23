@extends('layouts.admin')
@section('page-title', 'Detail Inspektor')
@section('content')
<div class="space-y-6">

    {{-- Back & action buttons --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.inspectors.index') }}" class="text-sm text-gray-500">← Kembali</a>
        <a href="{{ route('admin.inspectors.edit', $inspector) }}" class="btn-secondary btn-sm">Edit Inspektor</a>
    </div>

    {{-- Inspector detail card --}}
    <div class="card p-6">
        <div class="flex items-center gap-4 mb-6">
            <div class="w-14 h-14 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-2xl">
                {{ substr($inspector->name, 0, 1) }}
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $inspector->name }}</h2>
                <span class="badge badge-green text-xs">{{ ucfirst($inspector->role) }}</span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-1">Email</p>
                <p class="font-medium text-gray-700">{{ $inspector->email }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">No. Telepon</p>
                <p class="font-medium text-gray-700">{{ $inspector->phone ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">Alamat</p>
                <p class="font-medium text-gray-700">{{ $inspector->address ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">Role</p>
                <p class="font-medium text-gray-700">{{ ucfirst($inspector->role) }}</p>
            </div>
        </div>
    </div>

    {{-- Assigned bookings --}}
    <div class="card overflow-hidden">
        <div class="px-4 py-3 border-b">
            <h3 class="font-semibold text-gray-700 text-sm">Riwayat Tugas (10 Terbaru)</h3>
        </div>
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th>Kode Booking</th>
                    <th>Tanggal</th>
                    <th>Kendaraan</th>
                    <th>Paket</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @forelse($inspector->assignedBookings as $booking)
                <tr class="hover:bg-gray-50">
                    <td class="text-sm font-medium font-mono">{{ $booking->booking_code }}</td>
                    <td class="text-sm text-gray-600">{{ $booking->booking_date?->format('d M Y') ?? '-' }}</td>
                    <td class="text-sm">
                        @if($booking->vehicle)
                            {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}
                            <span class="text-gray-400 text-xs block">{{ $booking->vehicle->license_plate }}</span>
                        @else
                            <span class="text-gray-400">-</span>
                        @endif
                    </td>
                    <td class="text-sm">{{ $booking->package?->name ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center py-8 text-gray-400">Belum ada tugas</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
