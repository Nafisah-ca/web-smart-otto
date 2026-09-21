@extends('layouts.app')
@section('title', 'Layanan Kami')
@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <div class="text-center mb-12">
        <h1 class="text-4xl font-bold text-gray-900">Paket Layanan Inspeksi</h1>
        <p class="text-gray-500 mt-3">Pilih paket yang paling sesuai dengan kebutuhan kendaraan Anda</p>
    </div>
    <div class="space-y-8">
        @foreach($packages as $pkg)
        <div class="card p-6 md:p-8">
            <div class="flex flex-col md:flex-row md:items-start gap-6">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-3">
                        <span class="text-4xl">{{ $pkg->icon ?? '🔧' }}</span>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $pkg->name }}</h2>
                            <p class="text-primary-600 font-bold text-xl">{{ $pkg->formatted_price }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $pkg->description }}</p>
                    <p class="text-sm text-gray-500">⏱️ Estimasi waktu: {{ $pkg->duration_estimate }} menit</p>
                </div>
                <div class="md:w-80">
                    @if($pkg->checklistItems->count())
                    @php $grouped = $pkg->checklistItems->groupBy('category'); @endphp
                    <h3 class="font-semibold text-gray-700 mb-3 text-sm uppercase tracking-wide">Yang Dicek:</h3>
                    <div class="space-y-3">
                        @foreach($grouped as $cat => $items)
                        <div>
                            <p class="text-xs font-semibold text-gray-500 mb-1">{{ $cat }}</p>
                            <div class="flex flex-wrap gap-1">
                                @foreach($items as $item)
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-0.5 rounded">{{ $item->item_name }}</span>
                                @endforeach
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    <a href="{{ route('booking.create', ['package' => $pkg->id]) }}" class="btn-primary w-full justify-center mt-4">Pilih Paket Ini</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
