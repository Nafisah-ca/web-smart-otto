@extends('layouts.inspector')
@section('title', 'Tugas Saya')
@section('content')
<div class="space-y-4">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-gray-900">Tugas Saya</h2>
    </div>

    <form method="GET" class="flex gap-2">
        <select name="status" class="form-input text-sm" style="max-width:200px" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            @foreach(\App\Models\Booking::$statusLabels as $k=>$v)
            <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
            @endforeach
        </select>
    </form>

    @forelse($bookings as $b)
    <div class="card p-4">
        <div class="flex items-start justify-between gap-3">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="font-mono text-xs font-bold text-primary-600">{{ $b->booking_code }}</span>
                    <span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span>
                </div>
                <p class="font-medium text-gray-800">{{ $b->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $b->vehicle->brand }} {{ $b->vehicle->model }} · {{ $b->package->name }}</p>
                <p class="text-xs text-gray-400">{{ $b->booking_date->isoFormat('D MMM Y') }} · {{ substr($b->booking_time,0,5) }}</p>
            </div>
            <div class="flex flex-col gap-1.5">
                <a href="{{ route('inspector.tasks.show', $b) }}" class="btn-secondary btn-sm text-xs">Detail</a>
                @if(in_array($b->status, ['confirmed','waiting','on_progress']))
                <a href="{{ route('inspector.tasks.form', $b) }}" class="btn-primary btn-sm text-xs">🔧 Isi Hasil</a>
                @endif
            </div>
        </div>
    </div>
    @empty
    <div class="card p-10 text-center text-gray-400">Belum ada tugas yang di-assign.</div>
    @endforelse

    {{ $bookings->links() }}
</div>
@endsection
