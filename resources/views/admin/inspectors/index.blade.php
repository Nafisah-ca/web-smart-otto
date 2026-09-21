@extends('layouts.admin')
@section('page-title', 'Manajemen Inspektor')
@section('content')
<div class="space-y-4">
    <div class="flex justify-end">
        <a href="{{ route('admin.inspectors.create') }}" class="btn-primary btn-sm">+ Tambah Inspektor</a>
    </div>
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Total Tugas</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($inspectors as $i)
                <tr class="hover:bg-gray-50">
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-primary-100 rounded-full flex items-center justify-center text-primary-700 font-bold text-sm">{{ substr($i->name,0,1) }}</div>
                            <span class="text-sm font-medium">{{ $i->name }}</span>
                        </div>
                    </td>
                    <td class="text-sm text-gray-500">{{ $i->email }}</td>
                    <td class="text-sm">{{ $i->phone }}</td>
                    <td class="text-sm">{{ $i->assigned_bookings_count }}</td>
                    <td class="flex gap-1">
                        <a href="{{ route('admin.inspectors.edit', $i) }}" class="btn-secondary btn-sm text-xs">Edit</a>
                        <form method="POST" action="{{ route('admin.inspectors.destroy', $i) }}" onsubmit="return confirm('Hapus inspektor ini?')">@csrf @method('DELETE')
                            <button class="btn-danger btn-sm text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-400">Belum ada inspektor</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $inspectors->links() }}</div>
    </div>
</div>
@endsection
