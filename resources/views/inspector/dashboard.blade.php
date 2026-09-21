@extends('layouts.inspector')
@section('title', 'Dashboard Inspektor')
@section('content')
<div class="space-y-5">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Dashboard Inspektor</h2>
        <p class="text-sm text-gray-500">Halo, {{ auth()->user()->name }}</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach([
            ['label'=>'Total Tugas', 'val'=>$stats['total'],    'color'=>'blue'],
            ['label'=>'Hari Ini',    'val'=>$stats['today'],    'color'=>'orange'],
            ['label'=>'Dikerjakan', 'val'=>$stats['on_progress'],'color'=>'yellow'],
            ['label'=>'Selesai',    'val'=>$stats['completed'], 'color'=>'green'],
        ] as $s)
        <div class="card p-4 text-center">
            <p class="text-2xl font-bold text-{{ $s['color'] }}-600">{{ $s['val'] }}</p>
            <p class="text-xs text-gray-500 mt-1">{{ $s['label'] }}</p>
        </div>
        @endforeach
    </div>

    @if($todayTasks->count())
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">Jadwal Hari Ini</h3>
        <div class="space-y-3">
            @foreach($todayTasks as $b)
            <div class="card p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3">
                    <div class="text-center w-12">
                        <p class="text-sm font-bold text-primary-600">{{ substr($b->booking_time,0,5) }}</p>
                    </div>
                    <div>
                        <p class="font-medium text-sm text-gray-800">{{ $b->user->name }}</p>
                        <p class="text-xs text-gray-500">{{ $b->vehicle->brand }} {{ $b->vehicle->model }} · {{ $b->package->name }}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span>
                    <a href="{{ route('inspector.tasks.form', $b) }}" class="btn-primary btn-sm text-xs">Mulai</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    @if($pendingTasks->count())
    <div>
        <h3 class="font-semibold text-gray-800 mb-3">Tugas Pending</h3>
        <div class="card overflow-hidden">
            <table class="w-full table-auto">
                <thead><tr><th>Tanggal</th><th>Customer</th><th>Kendaraan</th><th>Status</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($pendingTasks as $b)
                    <tr>
                        <td class="text-sm">{{ $b->booking_date->format('d M') }} {{ substr($b->booking_time,0,5) }}</td>
                        <td class="text-sm font-medium">{{ $b->user->name }}</td>
                        <td class="text-sm">{{ $b->vehicle->brand }} {{ $b->vehicle->model }}</td>
                        <td><span class="badge-{{ $b->status_color }}">{{ $b->status_label }}</span></td>
                        <td><a href="{{ route('inspector.tasks.show', $b) }}" class="btn-secondary btn-sm text-xs">Detail</a></td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>
@endsection
