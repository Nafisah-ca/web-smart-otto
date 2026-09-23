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
                        {{-- Icon: pakai gambar jika ada, fallback SVG --}}
                        <div class="w-12 h-12 bg-primary-50 rounded-xl flex items-center justify-center flex-shrink-0 border border-primary-100">
                            @if(!empty($pkg->icon) && !str_contains($pkg->icon, '/'))
                                {{-- Masih emoji lama, tampilkan teks --}}
                                <span class="text-2xl">{{ $pkg->icon }}</span>
                            @elseif(!empty($pkg->icon))
                                <img src="{{ asset('storage/' . $pkg->icon) }}" alt="{{ $pkg->name }}" class="w-7 h-7 object-contain">
                            @else
                                <svg class="w-6 h-6 text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            @endif
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-900">{{ $pkg->name }}</h2>
                            <p class="text-primary-600 font-bold text-xl">{{ $pkg->formatted_price }}</p>
                        </div>
                    </div>
                    <p class="text-gray-600 mb-4">{{ $pkg->description }}</p>
                    <div class="flex items-center gap-1.5 text-sm text-gray-500">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Estimasi waktu: {{ $pkg->duration_estimate }} menit
                    </div>
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
                    <a href="{{ route('booking.create', ['package' => $pkg->id]) }}"
                       class="btn-primary w-full justify-center mt-4">Pilih Paket Ini</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
