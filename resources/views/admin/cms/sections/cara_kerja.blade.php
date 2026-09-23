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
        $steps = json_decode($data['cara_kerja_steps'] ?? '[]', true) ?? [];
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
                    <input type="text" name="cara_kerja_title"
                           value="{{ old('cara_kerja_title', $data['cara_kerja_title']) }}"
                           class="form-input w-full" placeholder="Contoh: Cara Kerja Smart Otto">
                </div>
                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Subjudul</label>
                    <textarea name="cara_kerja_subtitle" rows="2"
                              class="form-input w-full">{{ old('cara_kerja_subtitle', $data['cara_kerja_subtitle']) }}</textarea>
                </div>
            </div>
        </div>

        {{-- Langkah-langkah --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Langkah-Langkah</h2>
                <p class="text-xs text-gray-500 mt-0.5">Nomor langkah otomatis. Upload gambar icon per langkah (PNG/SVG/WebP, maks 1MB).</p>
            </div>

            <div id="stepList" class="divide-y divide-gray-100">
                @forelse($steps as $i => $step)
                <div class="px-5 py-5 space-y-4 step-item">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Langkah {{ $i + 1 }}</span>
                        <button type="button" onclick="removeStep(this)"
                                class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
                    </div>

                    {{-- Icon upload --}}
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0">
                            @if(!empty($step['icon']))
                            <img src="{{ asset($step['icon']) }}"
                                 alt="icon" class="w-12 h-12 object-contain rounded-lg border border-gray-200 bg-gray-50"
                                 id="preview-step-{{ $i }}">
                            @else
                            <div class="w-12 h-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center"
                                 id="preview-step-{{ $i }}">
                                <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                          d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                        <div class="flex-1 space-y-2">
                            <input type="hidden" name="step_icon_keep[{{ $i }}]" value="{{ $step['icon'] ?? '' }}">
                            <input type="file" name="step_icon[{{ $i }}]"
                                   accept="image/png,image/webp,image/svg+xml,image/jpeg"
                                   onchange="previewIcon(this, 'preview-step-{{ $i }}')"
                                   class="block w-full text-xs text-gray-500
                                          file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0
                                          file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700
                                          hover:file:bg-gray-200 cursor-pointer">
                            @if(!empty($step['icon']))
                            <label class="flex items-center gap-1.5 text-xs text-red-500 cursor-pointer">
                                <input type="checkbox" name="step_icon_remove[{{ $i }}]" value="1" class="rounded">
                                Hapus icon
                            </label>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Judul Langkah</label>
                        <input type="text" name="step_title[]" value="{{ $step['title'] ?? '' }}"
                               class="form-input w-full" placeholder="Contoh: Booking Online">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="step_desc[]" rows="2"
                                  class="form-input w-full">{{ $step['desc'] ?? '' }}</textarea>
                    </div>
                </div>
                @empty
                <div id="stepEmpty" class="px-5 py-8 text-center text-sm text-gray-400">
                    Belum ada langkah. Klik "Tambah Langkah" untuk menambahkan.
                </div>
                @endforelse
            </div>

            <div class="px-5 py-4 border-t border-gray-100 bg-gray-50 flex items-center justify-between">
                <button type="button" onclick="addStep()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">+ Tambah Langkah</button>
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
let sCount = {{ count($steps) }};

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

function addStep() {
    document.getElementById('stepEmpty')?.remove();
    const i = sCount++;
    const div = document.createElement('div');
    div.className = 'px-5 py-5 space-y-4 step-item border-t border-gray-100';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Langkah Baru</span>
            <button type="button" onclick="removeStep(this)" class="text-xs text-red-500 hover:text-red-700 font-medium">Hapus</button>
        </div>
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="w-12 h-12 rounded-lg border-2 border-dashed border-gray-300 bg-gray-50 flex items-center justify-center" id="preview-step-${i}">
                    <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                              d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="flex-1 space-y-2">
                <input type="hidden" name="step_icon_keep[${i}]" value="">
                <input type="file" name="step_icon[${i}]"
                       accept="image/png,image/webp,image/svg+xml,image/jpeg"
                       onchange="previewIcon(this, 'preview-step-${i}')"
                       class="block w-full text-xs text-gray-500 file:mr-2 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-medium file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            </div>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Judul Langkah</label>
            <input type="text" name="step_title[]" class="form-input w-full" placeholder="Contoh: Booking Online">
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Deskripsi</label>
            <textarea name="step_desc[]" rows="2" class="form-input w-full"></textarea>
        </div>
    `;
    document.getElementById('stepList').appendChild(div);
}

function removeStep(btn) { btn.closest('.step-item').remove(); }
</script>
@endpush
