@extends('layouts.admin')
@section('page-title', 'Checklist Item')
@section('content')
<div class="space-y-4">
    <div class="flex justify-between items-center">
        <form method="GET" class="flex gap-2">
            <select name="category" class="form-input text-sm" onchange="this.form.submit()">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat }}" {{ request('category')===$cat?'selected':'' }}>{{ $cat }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('admin.checklist-items.create') }}" class="btn-primary btn-sm">+ Tambah Item</a>
    </div>
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>#</th><th>Kategori</th><th>Nama Item</th><th>Status</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($items as $item)
                <tr>
                    <td class="text-xs text-gray-400">{{ $item->sort_order }}</td>
                    <td class="text-sm text-gray-600">{{ $item->category }}</td>
                    <td class="text-sm font-medium">{{ $item->item_name }}</td>
                    <td>
                        @if($item->is_active)
                        <span class="badge-green text-xs">Aktif</span>
                        @else
                        <span class="badge-gray text-xs">Nonaktif</span>
                        @endif
                    </td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.checklist-items.edit', $item) }}" class="btn-secondary btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.checklist-items.destroy', $item) }}" onsubmit="return confirm('Hapus?')">
                            @csrf @method('DELETE')
                            <button class="btn-danger btn-sm text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-400">Belum ada item</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $items->links() }}</div>
    </div>
</div>
@endsection
