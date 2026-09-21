@extends('layouts.admin')
@section('page-title', 'Kelola Booking')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div></div>
        <a href="{{ route('admin.bookings.create') }}" class="btn-primary btn-sm">+ Buat Booking</a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="card p-4 flex flex-wrap gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode/nama..." class="form-input text-sm" style="width:200px">
        <select name="status" class="form-input text-sm" style="width:180px">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Booking::$statusLabels as $k=>$v)
            <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
        <input type="date" name="date" value="{{ request('date') }}" class="form-input text-sm">
        <button type="submit" class="btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.bookings.index') }}" class="btn-secondary btn-sm">Reset</a>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead>
                <tr>
                    <th>Kode</th><th>Customer</th><th>Kendaraan</th><th>Paket</th>
                    <th>Tanggal</th><th>Inspektor</th><th>Status</th><th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($bookings as $b)
                <tr class="hover:bg-gray-50">
                    <td class="font-mono text-xs text-primary-600">{{ $b->booking_code }}</td>
                    <td>
                        <p class="text-sm font-medium">{{ $b->user->name }}</p>
                        <p class="text-xs text-gray-400">{{ $b->user->phone }}</p>
                    </td>
                    <td class="text-sm">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}<br><span class="text-xs text-gray-400">{{ $b->vehicle->plate_number }}</span></td>
                    <td class="text-sm">{{ $b->package->name }}</td>
                    <td class="text-sm">{{ $b->booking_date->format('d M Y') }}<br><span class="text-xs text-gray-400">{{ substr($b->booking_time,0,5) }}</span></td>
                    <td class="text-sm">{{ $b->inspector?->name ?? '<span class="text-gray-400">-</span>' }}</td>
                    <td><span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    <td><a href="{{ route('admin.bookings.show', $b) }}" class="btn-secondary btn-sm text-xs">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="8" class="text-center py-10 text-gray-400">Tidak ada data booking</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $bookings->links() }}</div>
    </div>
</div>
@endsection
