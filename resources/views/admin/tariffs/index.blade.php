@extends('layouts.admin')
@section('page-title', 'Master Tarif')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <div></div>
        <a href="{{ route('admin.tariffs.create') }}" class="btn-primary btn-sm">+ Tambah Tarif</a>
    </div>

    <form method="GET" class="flex gap-2 flex-wrap">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tarif..." class="form-input text-sm" style="width:200px">
        <select name="category" class="form-input text-sm" style="width:160px">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
            <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
            @endforeach
        </select>
        <button class="btn-primary btn-sm">Filter</button>
        <a href="{{ route('admin.tariffs.index') }}" class="btn-secondary btn-sm">Reset</a>
    </form>

    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>Nama</th><th>Kategori</th><th>Harga</th><th>Satuan</th><th>Aktif s/d</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($tariffs as $t)
                <tr class="hover:bg-gray-50">
                    <td class="text-sm font-medium">{{ $t->name }}</td>
                    <td><span class="badge-blue text-xs">{{ $t->category }}</span></td>
                    <td class="text-sm font-semibold">{{ $t->formatted_price }}</td>
                    <td class="text-sm text-gray-500">{{ $t->unit }}</td>
                    <td class="text-sm text-gray-500">{{ $t->active_until?->format('d/m/Y') ?? '∞' }}</td>
                    <td>
                        <form method="POST" action="{{ route('admin.tariffs.toggle', $t) }}">@csrf
                            <button class="badge {{ $t->is_active ? 'badge-green' : 'badge-gray' }} cursor-pointer">{{ $t->is_active ? 'Aktif' : 'Nonaktif' }}</button>
                        </form>
                    </td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.tariffs.edit', $t) }}" class="btn-secondary btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.tariffs.destroy', $t) }}" onsubmit="return confirm('Hapus?')">@csrf @method('DELETE')
                            <button class="btn-danger btn-sm text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center py-8 text-gray-400">Belum ada tarif</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t border-gray-100">{{ $tariffs->links() }}</div>
    </div>
</div>
@endsection
