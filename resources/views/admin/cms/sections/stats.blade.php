@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-2xl space-y-5">

    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $meta['label'] }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.cms.save', $section) }}" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">{{ $meta['label'] }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload icon gambar untuk tiap statistik (PNG/SVG/WebP, maks 1MB, disarankan 64×64px).</p>
            </div>

            @php
            $stats = [
                ['key' => 'customers',   'label' => 'Customer Puas',       'valueKey' => 'stat_customers',   'iconKey' => 'stat_icon_customers'],
                ['key' => 'inspections', 'label' => 'Inspeksi Selesai',    'valueKey' => 'stat_inspections', 'iconKey' => 'stat_icon_inspections'],
                ['key' => 'inspectors',  'label' => 'Teknisi Aktif',       'valueKey' => 'stat_inspectors',  'iconKey' => 'stat_icon_inspectors'],
                ['key' => 'years',       'label' => 'Tahun Pengalaman',    'valueKey' => 'stat_years',       'iconKey' => 'stat_icon_years'],
            ];
            @endphp

            <div class="divide-y divide-gray-100">
                @foreach($stats as $stat)
                <div class="px-5 py-5">
                    <p class="text-sm font-semibold text-gray-700 mb-3">{{ $stat['label'] }}</p>
                    <div class="flex items-start gap-4">

                        {{-- Preview icon --}}
                        <div class="flex-shrink-0">
                            @php $iconPath = $data[$stat['iconKey']] ?? ''; @endphp
                            @if($iconPath)
                            <img src="{{ asset($iconPath) }}"
                                 alt="icon" class="w-12 h-12 object-contain rounded-lg border border-gray-200 bg-gray-50"
                                 id="preview-stat-{{ $stat['key'] }}">
                            @else
                            <div class="w-12 h-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center"
                                 id="preview-stat-{{ $stat['key'] }}">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </div>

                        <div class="flex-1 space-y-3">
                            {{-- Upload icon --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Icon Gambar</label>
                                <input type="file" name="stat_icon_{{ $stat['key'] }}"
                                       accept="image/png,image/webp,image/svg+xml,image/jpeg"
                                       onchange="previewStatIcon(this, 'preview-stat-{{ $stat['key'] }}')"
                                       class="block w-full text-xs text-gray-500
                                              file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0
                                              file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700
                                              hover:file:bg-gray-200 cursor-pointer">
                                @if($iconPath)
                                <label class="flex items-center gap-1.5 text-xs text-red-500 mt-1 cursor-pointer">
                                    <input type="checkbox" name="stat_icon_{{ $stat['key'] }}_remove" value="1" class="rounded">
                                    Hapus icon
                                </label>
                                @endif
                            </div>

                            {{-- Nilai angka --}}
                            <div>
                                <label class="block text-xs font-medium text-gray-600 mb-1">Nilai Angka</label>
                                <input type="text" name="{{ $stat['valueKey'] }}"
                                       value="{{ old($stat['valueKey'], $data[$stat['valueKey']] ?? '') }}"
                                       class="form-input w-full" placeholder="Contoh: 500+">
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">Kembali</a>
            <button type="submit" class="btn-primary btn-sm">Simpan Perubahan</button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
function previewStatIcon(input, previewId) {
    if (!input.files || !input.files[0]) return;
    const reader = new FileReader();
    reader.onload = e => {
        const el = document.getElementById(previewId);
        if (!el) return;
        el.outerHTML = `<img src="${e.target.result}" alt="icon"
            class="w-12 h-12 object-contain rounded-lg border border-gray-200 bg-gray-50"
            id="${previewId}">`;
    };
    reader.readAsDataURL(input.files[0]);
}
</script>
@endpush
