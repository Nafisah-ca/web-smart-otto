@extends('layouts.customer')
@section('title', 'Kendaraan Saya')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Kendaraan Saya</h2>
        <a href="{{ route('customer.vehicles.create') }}" class="btn-primary btn-sm">+ Tambah Kendaraan</a>
    </div>

    @forelse($vehicles as $v)
    <div class="card p-5 flex items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center text-2xl">
                {{ $v->type === 'motor' ? '🏍️' : '🚗' }}
            </div>
            <div>
                <p class="font-semibold text-gray-800">{{ $v->brand }} {{ $v->model }} ({{ $v->year }})</p>
                <p class="text-sm text-gray-500">{{ $v->plate_number }} · {{ ucfirst($v->type) }} · {{ $v->color ?? '-' }}</p>
            </div>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <a href="{{ route('customer.vehicles.edit', $v) }}" class="btn-secondary btn-sm">Edit</a>
            <form method="POST" action="{{ route('customer.vehicles.destroy', $v) }}"
                  onsubmit="return confirm('Hapus kendaraan ini?')">
                @csrf @method('DELETE')
                <button type="submit" class="btn-danger btn-sm">Hapus</button>
            </form>
        </div>
    </div>
    @empty
    <div class="card p-10 text-center">
        <p class="text-gray-500">Belum ada kendaraan tersimpan.</p>
        <a href="{{ route('customer.vehicles.create') }}" class="btn-primary mt-4 inline-flex">Tambah Kendaraan</a>
    </div>
    @endforelse
</div>
@endsection
