@extends('layouts.admin')
@section('page-title', 'CMS — ' . $sectionMeta['label'])

@push('styles')
<style>
    .cms-field-card {
        transition: box-shadow 0.15s ease, border-color 0.15s ease;
    }
    .cms-field-card:focus-within {
        box-shadow: 0 0 0 3px rgba(59,130,246,0.1);
        border-color: #93c5fd;
    }
    .json-preview {
        font-family: 'Courier New', monospace;
        font-size: 0.75rem;
        line-height: 1.5;
    }
    .tab-btn.active {
        background: white;
        color: #1e40af;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
</style>
@endpush

@section('content')
<div class="space-y-5 max-w-4xl">

    {{-- Breadcrumb + Header --}}
    <div class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700 flex items-center gap-1">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
            CMS Konten
        </a>
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        <span class="text-gray-800 font-medium">{{ $sectionMeta['label'] }}</span>
    </div>

    {{-- Section Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center text-xl">
                {{ $sectionMeta['icon'] }}
            </div>
            <div>
                <h2 class="text-lg font-bold text-gray-900">{{ $sectionMeta['label'] }}</h2>
                <p class="text-xs text-gray-500">{{ $contents->count() }} field tersedia untuk diedit</p>
            </div>
        </div>

        {{-- Section nav quick-jump --}}
        <div class="hidden md:flex items-center gap-1">
            @foreach($sections as $sKey => $sMeta)
            <a href="{{ route('admin.cms.group', $sKey) }}"
               class="text-xs px-2.5 py-1 rounded-lg transition-all
                      {{ $sKey === $group ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                {{ $sMeta['icon'] }}
            </a>
            @endforeach
        </div>
    </div>

    {{-- Bulk Save Form --}}
    <form method="POST" action="{{ route('admin.cms.bulk', $group) }}" id="bulkForm">
        @csrf @method('PUT')

        <div class="space-y-4">
            @forelse($contents as $content)
            <div class="bg-white border border-gray-200 rounded-xl cms-field-card overflow-hidden">

                {{-- Field header --}}
                <div class="flex items-center justify-between px-5 py-3 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center gap-2">
                        <span class="font-medium text-sm text-gray-800">{{ $content->label }}</span>
                        <span class="text-xs text-gray-400 font-mono bg-gray-100 px-1.5 py-0.5 rounded">{{ $content->key }}</span>
                        @php
                        $typeBadge = [
                            'text'     => ['bg-gray-100 text-gray-600',   'TEXT'],
                            'textarea' => ['bg-blue-100 text-blue-700',   'TEXTAREA'],
                            'html'     => ['bg-orange-100 text-orange-700','HTML'],
                            'json'     => ['bg-purple-100 text-purple-700','JSON'],
                            'image'    => ['bg-green-100 text-green-700', 'IMAGE'],
                        ];
                        $tb = $typeBadge[$content->type] ?? ['bg-gray-100 text-gray-600', strtoupper($content->type)];
                        @endphp
                        <span class="text-xs font-semibold px-1.5 py-0.5 rounded {{ $tb[0] }}">{{ $tb[1] }}</span>
                    </div>

                    {{-- Quick save single item --}}
                    <button type="button"
                            onclick="saveSingle({{ $content->id }}, this)"
                            class="text-xs text-blue-600 hover:text-blue-800 font-medium px-2 py-1 rounded hover:bg-blue-50 transition-all">
                        Simpan
                    </button>
                </div>

                {{-- Field input --}}
                <div class="px-5 py-4">
                    @if($content->type === 'json')
                        {{-- JSON: tabs antara raw & preview --}}
                        <div class="space-y-2">
                            <div class="flex gap-1 bg-gray-100 p-0.5 rounded-lg w-fit">
                                <button type="button" onclick="switchTab(this, 'raw-{{ $content->id }}')"
                                        class="tab-btn active text-xs px-3 py-1 rounded-md font-medium transition-all">Edit JSON</button>
                                <button type="button" onclick="switchTab(this, 'preview-{{ $content->id }}')"
                                        class="tab-btn text-xs px-3 py-1 rounded-md font-medium text-gray-500 transition-all">Preview</button>
                            </div>

                            <div id="raw-{{ $content->id }}">
                                <textarea name="contents[{{ $content->id }}]"
                                          id="json-{{ $content->id }}"
                                          rows="8"
                                          class="form-input font-mono text-xs json-preview w-full"
                                          placeholder="Masukkan JSON valid..."
                                          oninput="updateJsonPreview({{ $content->id }})">{{ $content->value }}</textarea>
                                <p class="text-xs text-gray-400 mt-1">⚠️ Pastikan format JSON valid sebelum menyimpan.</p>
                            </div>

                            <div id="preview-{{ $content->id }}" class="hidden">
                                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 min-h-[8rem]" id="jsonPreview-{{ $content->id }}">
                                    {{-- Diisi JS --}}
                                </div>
                            </div>
                        </div>

                    @elseif($content->type === 'textarea' || $content->type === 'html')
                        <textarea name="contents[{{ $content->id }}]"
                                  rows="{{ $content->type === 'html' ? 6 : 4 }}"
                                  class="form-input text-sm w-full {{ $content->type === 'html' ? 'font-mono text-xs' : '' }}"
                                  placeholder="Masukkan konten...">{{ $content->value }}</textarea>
                        @if($content->type === 'html')
                        <p class="text-xs text-gray-400 mt-1">Mendukung tag HTML dasar.</p>
                        @endif

                    @elseif($content->type === 'image')
                        <div class="space-y-2">
                            @if($content->value)
                            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-lg border border-gray-200">
                                <img src="{{ $content->value }}" alt="preview" class="w-16 h-16 object-cover rounded-lg border border-gray-200">
                                <div class="text-xs text-gray-500">
                                    <p class="font-medium text-gray-700">URL Gambar Saat Ini</p>
                                    <p class="break-all mt-0.5">{{ $content->value }}</p>
                                </div>
                            </div>
                            @endif
                            <input type="text"
                                   name="contents[{{ $content->id }}]"
                                   value="{{ $content->value }}"
                                   class="form-input text-sm w-full"
                                   placeholder="Masukkan URL gambar (https://...)">
                        </div>

                    @else
                        {{-- Default: text input --}}
                        <input type="text"
                               name="contents[{{ $content->id }}]"
                               value="{{ $content->value }}"
                               class="form-input text-sm w-full"
                               placeholder="Masukkan nilai...">
                    @endif
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-xl border border-gray-200">
                <div class="text-4xl mb-3">📭</div>
                <p class="text-gray-500 font-medium">Belum ada konten di section ini.</p>
                <p class="text-sm text-gray-400 mt-1">Jalankan seeder untuk menambah data default.</p>
            </div>
            @endforelse
        </div>

        {{-- Sticky footer action bar --}}
        @if($contents->count() > 0)
        <div class="sticky bottom-0 bg-white border-t border-gray-200 mt-6 -mx-6 px-6 py-4 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                <span id="savedIndicator" class="hidden text-green-600 font-medium">✅ Semua perubahan tersimpan</span>
                <span id="unsavedIndicator" class="text-amber-600 font-medium hidden">⚠️ Ada perubahan belum tersimpan</span>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">← Kembali</a>
                <button type="submit" class="btn-primary btn-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                    Simpan Semua
                </button>
            </div>
        </div>
        @endif
    </form>

</div>
@endsection

@push('scripts')
<script>
// Tab switch untuk JSON editor
function switchTab(btn, targetId) {
    const wrapper = btn.closest('.space-y-2');
    wrapper.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    btn.classList.add('active');

    // Ambil semua div tab panels (raw & preview)
    const allPanels = wrapper.querySelectorAll('[id^="raw-"], [id^="preview-"]');
    allPanels.forEach(p => p.classList.add('hidden'));

    const target = document.getElementById(targetId);
    if (target) {
        target.classList.remove('hidden');
        // Kalau preview, render dulu
        if (targetId.startsWith('preview-')) {
            const id = targetId.replace('preview-', '');
            updateJsonPreview(id);
        }
    }
}

// Render JSON preview sederhana
function updateJsonPreview(id) {
    const textarea = document.getElementById('json-' + id);
    const preview  = document.getElementById('jsonPreview-' + id);
    if (!textarea || !preview) return;

    try {
        const data = JSON.parse(textarea.value);
        preview.innerHTML = renderJsonPreview(data);
    } catch(e) {
        preview.innerHTML = '<p class="text-red-500 text-xs">⚠️ JSON tidak valid: ' + e.message + '</p>';
    }
}

function escHtml(str) {
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;');
}

function renderJsonPreview(data) {
    if (Array.isArray(data)) {
        return data.map((item, i) => {
            if (typeof item === 'object' && item !== null) {
                const rows = Object.entries(item).map(([k, v]) =>
                    `<div class="flex gap-2 text-xs"><span class="font-semibold text-gray-600 w-24 flex-shrink-0">${escHtml(k)}:</span><span class="text-gray-800">${escHtml(String(v))}</span></div>`
                ).join('');
                return `<div class="border border-gray-200 rounded-lg p-3 bg-white mb-2">
                            <div class="text-xs font-bold text-gray-400 mb-2">Item ${i + 1}</div>
                            ${rows}
                        </div>`;
            }
            return `<div class="text-xs text-gray-700 py-1">${i + 1}. ${escHtml(String(item))}</div>`;
        }).join('');
    } else if (typeof data === 'object' && data !== null) {
        return Object.entries(data).map(([k, v]) =>
            `<div class="flex gap-2 text-xs py-1 border-b border-gray-100"><span class="font-semibold text-gray-600 w-32 flex-shrink-0">${escHtml(k)}</span><span class="text-gray-800">${escHtml(String(v))}</span></div>`
        ).join('');
    }
    return `<pre class="text-xs text-gray-700">${escHtml(JSON.stringify(data, null, 2))}</pre>`;
}

// Quick save single item via AJAX
function saveSingle(contentId, btn) {
    const form     = document.getElementById('bulkForm');
    const input    = form.querySelector(`[name="contents[${contentId}]"]`);
    if (!input) return;

    const original = btn.textContent;
    btn.textContent = '⏳';
    btn.disabled = true;

    const data = new FormData();
    data.append('_method', 'PUT');
    data.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    data.append('value', input.value);

    fetch(`/admin/cms/${contentId}`, { method: 'POST', body: data })
        .then(r => {
            if (r.ok || r.redirected) {
                btn.textContent = '✅ Tersimpan';
                btn.classList.add('text-green-600');
                btn.classList.remove('text-blue-600');
                setTimeout(() => {
                    btn.textContent = original;
                    btn.classList.remove('text-green-600');
                    btn.classList.add('text-blue-600');
                    btn.disabled = false;
                }, 2000);
            } else {
                btn.textContent = '❌ Gagal';
                btn.disabled = false;
                setTimeout(() => { btn.textContent = original; }, 2000);
            }
        })
        .catch(() => {
            btn.textContent = '❌ Gagal';
            btn.disabled = false;
        });
}

// Unsaved indicator
document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('bulkForm');
    const unsaved = document.getElementById('unsavedIndicator');
    if (!form || !unsaved) return;

    form.addEventListener('input', () => {
        unsaved.classList.remove('hidden');
        document.getElementById('savedIndicator')?.classList.add('hidden');
    });

    form.addEventListener('submit', () => {
        unsaved.classList.add('hidden');
        const saved = document.getElementById('savedIndicator');
        if (saved) { saved.classList.remove('hidden'); }
    });

    // Init JSON previews
    document.querySelectorAll('[id^="json-"]').forEach(ta => {
        const id = ta.id.replace('json-', '');
        updateJsonPreview(id);
    });
});
</script>
@endpush
