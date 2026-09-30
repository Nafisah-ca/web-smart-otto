@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-3xl space-y-5">

    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $meta['label'] }}</span>
    </nav>

    @php
        $items = json_decode($data['kategori_items'] ?? '[]', true) ?? [];
    @endphp

    <form method="POST" action="{{ route('admin.cms.save', $section) }}" enctype="multipart/form-data">
        @csrf

        {{-- Judul Section --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden mb-4">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Judul Section</h2>
            </div>
            <div class="px-5 py-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Judul</label>
                <input type="text" name="kategori_title"
                       value="{{ old('kategori_title', $data['kategori_title'] ?? 'Pilih Kategori') }}"
                       class="form-input w-full" placeholder="Contoh: Pilih Kategori">
            </div>
        </div>

        {{-- Daftar Kategori --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Daftar Kategori</h2>
                <p class="text-xs text-gray-500 mt-0.5">Tiap kategori punya nama, range harga, gambar, dan daftar model.</p>
            </div>

            <div id="katList" class="divide-y divide-gray-100">
                @forelse($items as $i => $item)
                <div class="px-5 py-5 space-y-4 kat-item">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori {{ $i + 1 }}</span>
                        <button type="button" onclick="removeKat(this)" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                            <input type="text" name="kat_name[]" value="{{ $item['name'] ?? '' }}"
                                   class="form-input w-full" placeholder="Contoh: Kat. 1 / SUV">
                        </div>
                        <div class="space-y-1">
                            <label class="block text-sm font-medium text-gray-700">Range Harga</label>
                            <input type="text" name="kat_harga[]" value="{{ $item['harga'] ?? '' }}"
                                   class="form-input w-full" placeholder="Contoh: Rp 500.000 - 800.000,-">
                        </div>
                    </div>

                    {{-- Gambar kategori --}}
                    <div class="space-y-2">
                        <label class="block text-sm font-medium text-gray-700">Gambar Kendaraan</label>
                        <div class="flex items-start gap-4">
                            <div class="flex-shrink-0">
                                @if(!empty($item['img']))
                                <img src="{{ asset($item['img']) }}" alt="img"
                                     class="w-24 h-16 object-cover rounded-lg border border-gray-200"
                                     id="prev-kat-{{ $i }}">
                                @else
                                <div class="w-24 h-16 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center"
                                     id="prev-kat-{{ $i }}">
                                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                @endif
                            </div>
                            <div class="flex-1 space-y-2">
                                <input type="hidden" name="kat_img_keep[{{ $i }}]" value="{{ $item['img'] ?? '' }}">
                                <input type="file" name="kat_img[{{ $i }}]"
                                       accept="image/png,image/jpeg,image/webp"
                                       onchange="prevKat(this,'prev-kat-{{ $i }}')"
                                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                                @if(!empty($item['img']))
                                <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                                    <input type="checkbox" name="kat_img_remove[{{ $i }}]" value="1" class="rounded">
                                    Hapus gambar
                                </label>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Daftar Model --}}
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Daftar Model Kendaraan</label>
                        <textarea name="kat_models[]" rows="5"
                                  class="form-input w-full font-mono text-xs"
                                  placeholder="Tulis tiap model di baris baru:&#10;Alphard&#10;Land Cruiser&#10;Palisade">{{ implode("\n", $item['models'] ?? []) }}</textarea>
                        <p class="text-xs text-gray-400">Tiap baris = satu model. Akan ditampilkan dalam 2 kolom.</p>
                    </div>
                </div>
                @empty
                <div id="katEmpty" class="px-5 py-10 text-center text-sm text-gray-400">
                    Belum ada kategori. Klik "Tambah Kategori" untuk menambahkan.
                </div>
                @endforelse
            </div>

            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <button type="button" onclick="addKat()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Tambah Kategori</button>
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
let kc = {{ count($items) }};

function prevKat(input, id) {
    if (!input.files || !input.files[0]) return;
    const r = new FileReader();
    r.onload = e => {
        const el = document.getElementById(id);
        if (el) el.outerHTML = `<img src="${e.target.result}" class="w-24 h-16 object-cover rounded-lg border border-gray-200" id="${id}">`;
    };
    r.readAsDataURL(input.files[0]);
}

function addKat() {
    document.getElementById('katEmpty')?.remove();
    const i = kc++;
    const div = document.createElement('div');
    div.className = 'px-5 py-5 space-y-4 kat-item border-t border-gray-100';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Kategori Baru</span>
            <button type="button" onclick="removeKat(this)" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
        </div>
        <div class="grid grid-cols-2 gap-4">
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Nama Kategori</label>
                <input type="text" name="kat_name[]" class="form-input w-full" placeholder="Contoh: Kat. 1 / SUV">
            </div>
            <div class="space-y-1">
                <label class="block text-sm font-medium text-gray-700">Range Harga</label>
                <input type="text" name="kat_harga[]" class="form-input w-full" placeholder="Contoh: Rp 500.000 - 800.000,-">
            </div>
        </div>
        <div class="space-y-2">
            <label class="block text-sm font-medium text-gray-700">Gambar Kendaraan</label>
            <div class="flex items-start gap-4">
                <div class="w-24 h-16 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center flex-shrink-0" id="prev-kat-${i}">
                    <svg class="w-6 h-6 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div class="flex-1">
                    <input type="hidden" name="kat_img_keep[${i}]" value="">
                    <input type="file" name="kat_img[${i}]" accept="image/png,image/jpeg,image/webp"
                           onchange="prevKat(this,'prev-kat-${i}')"
                           class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
                </div>
            </div>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Daftar Model Kendaraan</label>
            <textarea name="kat_models[]" rows="5" class="form-input w-full font-mono text-xs" placeholder="Tulis tiap model di baris baru"></textarea>
            <p class="text-xs text-gray-400">Tiap baris = satu model.</p>
        </div>
    `;
    document.getElementById('katList').appendChild(div);
}

function removeKat(btn) { btn.closest('.kat-item').remove(); }
</script>
@endpush
