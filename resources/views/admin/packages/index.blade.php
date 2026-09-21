@extends('layouts.admin')
@section('page-title', 'Paket Inspeksi')
@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('admin.packages.create') }}" class="btn-primary btn-sm">+ Tambah Paket</a>
    </div>
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>Nama</th><th>Harga</th><th>Durasi</th><th>Total Booking</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($packages as $p)
                <tr class="hover:bg-gray-50">
                    <td class="font-medium text-sm">{{ $p->icon }} {{ $p->name }}</td>
                    <td class="text-sm font-semibold">{{ $p->formatted_price }}</td>
                    <td class="text-sm text-gray-500">{{ $p->duration_estimate }} menit</td>
                    <td class="text-sm">{{ $p->bookings_count }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.packages.toggle', $p) }}">@csrf
                            <button class="badge {{ $p->is_active ? 'badge-green' : 'badge-gray' }} cursor-pointer">{{ $p->is_active?'Aktif':'Nonaktif' }}</button>
                        </form>
                    </td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.packages.edit', $p) }}" class="btn-secondary btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.packages.destroy', $p) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                            <button class="btn-danger btn-sm text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center py-8 text-gray-400">Belum ada paket</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $packages->links() }}</div>
    </div>
</div>
@endsection
