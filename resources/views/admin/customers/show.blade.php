@extends('layouts.admin')
@section('page-title', 'Detail Customer')
@section('content')
<div class="max-w-3xl space-y-5">
    <a href="{{ route('admin.customers.index') }}" class="text-sm text-gray-500">← Kembali</a>
    <div class="card p-5 grid grid-cols-2 gap-3 text-sm">
        <div><p class="text-gray-500">Nama</p><p class="font-semibold">{{ $user->name }}</p></div>
        <div><p class="text-gray-500">Email</p><p>{{ $user->email }}</p></div>
        <div><p class="text-gray-500">Telepon</p><p>{{ $user->phone }}</p></div>
        <div><p class="text-gray-500">Alamat</p><p>{{ $user->address ?? '-' }}</p></div>
        <div><p class="text-gray-500">Terdaftar</p><p>{{ $user->created_at->isoFormat('D MMMM Y') }}</p></div>
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">Kendaraan</h3>
        @foreach($user->vehicles as $v)
        <div class="card p-4 mb-2 text-sm flex justify-between">
            <span class="font-medium">{{ $v->brand }} {{ $v->model }}</span>
            <span class="text-gray-500">{{ $v->plate_number }}</span>
        </div>
        @endforeach
    </div>
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">Riwayat Booking</h3>
        <div class="card overflow-hidden">
            <table class="w-full table-auto">
                <thead><tr><th>Kode</th><th>Paket</th><th>Tanggal</th><th>Status</th></tr></thead>
                <tbody>
                    @foreach($user->bookings as $b)
                    <tr>
                        <td class="font-mono text-xs text-primary-600">{{ $b->booking_code }}</td>
                        <td class="text-sm">{{ $b->package->name }}</td>
                        <td class="text-sm">{{ $b->booking_date->format('d/m/Y') }}</td>
                        <td><span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
