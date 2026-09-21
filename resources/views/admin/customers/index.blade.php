@extends('layouts.admin')
@section('page-title', 'Data Customer')
@section('content')
<div class="space-y-4">
    <form method="GET" class="flex gap-2">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/email..." class="form-input text-sm" style="width:250px">
        <button class="btn-primary btn-sm">Cari</button>
        <a href="{{ route('admin.customers.index') }}" class="btn-secondary btn-sm">Reset</a>
    </form>
    <div class="card overflow-hidden">
        <table class="w-full table-auto">
            <thead><tr><th>Nama</th><th>Email</th><th>Telepon</th><th>Total Booking</th><th>Aksi</th></tr></thead>
            <tbody>
                @forelse($customers as $c)
                <tr class="hover:bg-gray-50">
                    <td class="text-sm font-medium">{{ $c->name }}</td>
                    <td class="text-sm text-gray-500">{{ $c->email }}</td>
                    <td class="text-sm">{{ $c->phone }}</td>
                    <td class="text-sm">{{ $c->bookings_count }}</td>
                    <td><a href="{{ route('admin.customers.show', $c) }}" class="btn-secondary btn-sm text-xs">Detail</a></td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center py-8 text-gray-400">Belum ada customer</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-4 py-3 border-t">{{ $customers->links() }}</div>
    </div>
</div>
@endsection
