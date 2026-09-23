@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-2xl space-y-5">

    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $meta['label'] }}</span>
    </nav>

    {{-- Identitas Website --}}
    <form method="POST" action="{{ route('admin.cms.save', $section) }}" class="space-y-4">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Identitas Website</h2>
            </div>
            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Nama Website</label>
                    <input type="text" name="site_name" value="{{ old('site_name', $data['site_name']) }}"
                           class="form-input w-full" placeholder="Contoh: Smart Otto">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Tagline Global</label>
                    <input type="text" name="site_tagline" value="{{ old('site_tagline', $data['site_tagline']) }}"
                           class="form-input w-full" placeholder="Contoh: Inspeksi Kendaraan Profesional">
                </div>

            </div>
        </div>

        {{-- Tentang Kami --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Tentang Kami</h2>
            </div>
            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Judul Halaman</label>
                    <input type="text" name="about_title" value="{{ old('about_title', $data['about_title']) }}"
                           class="form-input w-full" placeholder="Tentang Smart Otto">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Deskripsi Perusahaan</label>
                    <textarea name="about_content" rows="4"
                              class="form-input w-full">{{ old('about_content', $data['about_content']) }}</textarea>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Visi</label>
                    <textarea name="about_vision" rows="2"
                              class="form-input w-full" placeholder="Visi perusahaan...">{{ old('about_vision', $data['about_vision']) }}</textarea>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Misi</label>
                    <textarea name="about_mission" rows="3"
                              class="form-input w-full" placeholder="Misi perusahaan...">{{ old('about_mission', $data['about_mission']) }}</textarea>
                </div>

            </div>
        </div>

        <div class="flex justify-end">
            <button type="submit" class="btn-primary btn-sm">Simpan Identitas & Tentang Kami</button>
        </div>

    </form>

    {{-- FAQ — form terpisah --}}
    @php
        $faqs = json_decode($data['faq_items'] ?? '[]', true) ?? [];
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
            <div>
                <h2 class="font-semibold text-gray-800">FAQ (Pertanyaan Umum)</h2>
                <p class="text-xs text-gray-500 mt-0.5">Pertanyaan yang sering ditanyakan, tampil di beranda.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.cms.faq.save') }}" id="faqForm">
            @csrf

            <div id="faqList" class="divide-y divide-gray-100">
                @forelse($faqs as $i => $faq)
                <div class="px-5 py-4 space-y-3 faq-item" data-index="{{ $i }}">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pertanyaan {{ $i + 1 }}</span>
                        <button type="button" onclick="removeFaq(this)"
                                class="text-xs text-red-500 hover:text-red-700">Hapus</button>
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
                        <input type="text" name="faq_q[]" value="{{ $faq['q'] ?? '' }}"
                               class="form-input w-full" placeholder="Tulis pertanyaan...">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">Jawaban</label>
                        <textarea name="faq_a[]" rows="2"
                                  class="form-input w-full" placeholder="Tulis jawaban...">{{ $faq['a'] ?? '' }}</textarea>
                    </div>
                </div>
                @empty
                <div id="faqEmpty" class="px-5 py-8 text-center text-sm text-gray-400">
                    Belum ada FAQ. Klik "Tambah Pertanyaan" untuk menambahkan.
                </div>
                @endforelse
            </div>

            <div class="px-5 py-4 border-t border-gray-100 flex items-center justify-between bg-gray-50">
                <button type="button" onclick="addFaq()"
                        class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                    + Tambah Pertanyaan
                </button>
                <button type="submit" class="btn-primary btn-sm">Simpan FAQ</button>
            </div>
        </form>
    </div>

    <div class="pb-4">
        <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">Kembali ke Daftar</a>
    </div>

</div>

@endsection

@push('scripts')
<script>
let faqCount = {{ count($faqs) }};

function addFaq() {
    const empty = document.getElementById('faqEmpty');
    if (empty) empty.remove();

    const list = document.getElementById('faqList');
    faqCount++;
    const div = document.createElement('div');
    div.className = 'px-5 py-4 space-y-3 faq-item border-t border-gray-100';
    div.innerHTML = `
        <div class="flex items-center justify-between">
            <span class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Pertanyaan ${faqCount}</span>
            <button type="button" onclick="removeFaq(this)" class="text-xs text-red-500 hover:text-red-700">Hapus</button>
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Pertanyaan</label>
            <input type="text" name="faq_q[]" class="form-input w-full" placeholder="Tulis pertanyaan...">
        </div>
        <div class="space-y-1">
            <label class="block text-sm font-medium text-gray-700">Jawaban</label>
            <textarea name="faq_a[]" rows="2" class="form-input w-full" placeholder="Tulis jawaban..."></textarea>
        </div>
    `;
    list.appendChild(div);
}

function removeFaq(btn) {
    btn.closest('.faq-item').remove();
}
</script>
@endpush
