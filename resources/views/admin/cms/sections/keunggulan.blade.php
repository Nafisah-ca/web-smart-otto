@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-2xl space-y-5">

    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $meta['label'] }}</span>
    </nav>

    @php
        $items = json_decode($data['keunggulan_items'] ?? '[]', true) ?? [];
    @endphp

    <form method="POST" action="{{ route('admin.cms.save', $section) }}" enctype="multipart/form-data">
        @csrf

        {{-- Judul Section --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Judul Section</h2>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" name="keunggulan_title"
                           value="{{ old('keunggulan_title', $data['keunggulan_title']) }}"
                           class="form-input w-full" placeholder="Contoh: Mengapa Memilih Smart Otto?">
                </div>
                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Subjudul</label>
                    <textarea name="keunggulan_subtitle" rows="2"
                              class="form-input w-full">{{ old('keunggulan_subtitle', $data['keunggulan_subtitle']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Daftar Item --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Daftar Keunggulan</h2>
                <p class="text-xs text-gray-500 mt-0.5">Upload gambar icon (PNG/SVG/WebP, maks 1MB, disarankan ukuran 64×64px).</p>
            </div>

            <div id="itemList" class="divide-y divide-gray-100">
                @forelse($items as $i => $item)
                <div class="px-5 py-5 space-y-4 keunggulan-item">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item {{ $i + 1 }}</span>
                        <button type="button" onclick="removeItem(this)"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                    </div>

                    {{-- Icon upload --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if(!empty($item['icon']))
                            <img src="{{ asset($item['icon']) }}"
                                 alt="icon" class="w-12 h-12 object-contain rounded-lg border border-gray-200 bg-gray-50"
                                 id="preview-keunggulan-{{ $i }}">
                            @else
                            <div class="w-12 h-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center"
                                 id="preview-keunggulan-{{ $i }}">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2">
                            <input type="hidden" name="item_icon_keep[{{ $i }}]" value="{{ $item['icon'] ?? '' }}">
                            <input type="file" name="item_icon[{{ $i }}]"
                                   accept="image/png,image/webp,image/svg+xml,image/jpeg"
                                   onchange="previewIcon(this, 'preview-keunggulan-{{ $i }}')"
                                   class="block w-full text-xs text-gray-500
                                          file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0
                                          file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700
                                          hover:file:bg-gray-200 cursor-pointer">
                            @if(!empty($item['icon']))
                            <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                                <input type="checkbox" name="item_icon_remove[{{ $i }}]" value="1" class="rounded">
                                Hapus icon
                            </label>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Judul</label>
                        <input type="text" name="item_title[]" value="{{ $item['title'] ?? '' }}"
                               class="form-input w-full" placeholder="Contoh: Pemeriksaan 150+ Titik">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="item_desc[]" rows="2"
                                  class="form-input w-full">{{ $item['desc'] ?? '' }}</textarea>
                    </div>
                </div>
                @empty
                <div id="itemEmpty" class="px-5 py-8 text-center text-sm text-gray-400">
                    Belum ada item. Klik "Tambah Item" untuk menambahkan.
                </div>
                @endforelse
            </div>

            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <button type="button" onclick="addItem()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Tambah Item</button>
                <div class="flex gap-2">
                    <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">Kembali</a>
                    <button type="submit" class="btn-primary btn-sm">Simpan Perubahan</button>
                </div>
            </div>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
let kCount = {{ count($items) }};

function previewIcon(input, previewId) {
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

function addItem() {
    document.getElementById('itemEmpty')?.remove();
    const i = kCount++;
    const div = document.createElement('div');
    div.className = 'px-5 py-5 space-y-4 keunggulan-item border-t border-gray-100';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Item Baru</span>
            <button type="button" onclick="removeItem(this)" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
        </div>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center" id="preview-keunggulan-${i}">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <input type="hidden" name="item_icon_keep[${i}]" value="">
                <input type="file" name="item_icon[${i}]"
                       accept="image/png,image/webp,image/svg+xml,image/jpeg"
                       onchange="previewIcon(this, 'preview-keunggulan-${i}')"
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Judul</label>
            <input type="text" name="item_title[]" class="form-input w-full" placeholder="Contoh: Pemeriksaan 150+ Titik">
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="item_desc[]" rows="2" class="form-input w-full"></textarea>
        </div>
    `;
    document.getElementById('itemList').appendChild(div);
}

function removeItem(btn) { btn.closest('.keunggulan-item').remove(); }
</script>
@endpush
