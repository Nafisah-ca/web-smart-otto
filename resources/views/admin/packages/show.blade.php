@extends('layouts.admin')
@section('page-title', 'Detail Paket')
@section('content')
<div class="space-y-6">

    {{-- Back & action buttons --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.packages.index') }}" class="text-sm text-gray-500">← Kembali</a>
        <a href="{{ route('admin.packages.edit', $package) }}" class="btn-secondary btn-sm">Edit Paket</a>
    </div>

    {{-- Package detail card --}}
    <div class="card p-6">
        <div class="flex items-center gap-3 mb-6">
            @if($package->icon)
                <span class="text-3xl">{{ $package->icon }}</span>
            @endif
            <div>
                <h2 class="text-lg font-semibold text-gray-800">{{ $package->name }}</h2>
                <span class="badge {{ $package->is_active ? 'badge-green' : 'badge-gray' }} text-xs">
                    {{ $package->is_active ? 'Aktif' : 'Nonaktif' }}
                </span>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400 text-xs mb-1">Harga</p>
                <p class="font-semibold text-gray-700">{{ $package->formatted_price }}</p>
            </div>
            <div>
                <p class="text-gray-400 text-xs mb-1">Estimasi Durasi</p>
                <p class="font-medium text-gray-700">{{ $package->duration_estimate }} menit</p>
            </div>
            <div class="sm:col-span-2">
                <p class="text-gray-400 text-xs mb-1">Deskripsi</p>
                <p class="font-medium text-gray-700">{{ $package->description ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Checklist items grouped by category --}}
    <div class="card overflow-hidden">
        <div class="px-4 py-3 border-b">
            <h3 class="font-semibold text-gray-700 text-sm">Item Checklist</h3>
        </div>

        @if($package->checklistItems->isEmpty())
            <p class="text-center py-8 text-gray-400 text-sm">Belum ada item checklist</p>
        @else
            @foreach($package->checklistItems->sortBy(['category', 'sort_order'])->groupBy('category') as $category => $items)
            <div class="border-b last:border-b-0">
                <div class="px-4 py-2 bg-gray-50">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">{{ $category ?? 'Umum' }}</p>
                </div>
                <table class="w-full table-auto">
                    <tbody>
                        @foreach($items->sortBy('sort_order') as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="text-sm font-medium text-gray-700 pl-6">{{ $item->item_name }}</td>
                            <td class="text-sm text-gray-500">{{ $item->description ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @endforeach
        @endif
    </div>

</div>
@endsection
