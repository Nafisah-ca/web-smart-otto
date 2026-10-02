@extends('layouts.admin')
@section('page-title', 'CMS — Kenapa Harus Kami?')
@section('content')

<div class="max-w-2xl space-y-5">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">Kenapa Harus Kami?</span>
    </nav>

    {{-- Info --}}
    <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 text-sm text-primary-800">
        <p class="font-semibold mb-1">💡 Section ini tampil di halaman beranda</p>
        <p>Terdiri dari judul, subjudul, dan 4 kartu keunggulan. Kartu ke-2 (Laporan Online) memiliki tombol yang membuka file PDF contoh laporan.</p>
    </div>

    <form method="POST"
          action="{{ route('admin.cms.save', $section) }}"
          enctype="multipart/form-data"
          class="space-y-5">
        @csrf

        {{-- ── Teks Section ──────────────────────────────────────────── --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-sm text-gray-800">Judul Section</h2>
            </div>
            <div class="divide-y divide-gray-100">
                <div class="px-5 py-4 space-y-1">
                    <label class="form-label">Judul
                        <span class="text-gray-400 font-normal text-xs ml-1">(ditampilkan besar di atas)</span>
                    </label>
                    <input type="text" name="kenapa_judul"
                           value="{{ old('kenapa_judul', $data['kenapa_judul'] ?? 'Kenapa Harus Kami?') }}"
                           class="form-input w-full" placeholder="Kenapa Harus Kami?">
                </div>
                <div class="px-5 py-4 space-y-1">
                    <label class="form-label">Subjudul
                        <span class="text-gray-400 font-normal text-xs ml-1">(opsional)</span>
                    </label>
                    <textarea name="kenapa_subjudul" rows="2" class="form-input w-full"
                              placeholder="Standar inspeksi tinggi...">{{ old('kenapa_subjudul', $data['kenapa_subjudul'] ?? '') }}</textarea>
                </div>
            </div>
        </div>

        {{-- ── Kartu 1 ────────────────────────────────────────────────── --}}
        @foreach([
            ['n'=>1,'judul_default'=>'Alat Canggih',   'desc_default'=>'Peralatan berkualitas yang membuat inspektor kami memiliki akurasi tinggi dalam pengecekan.'],
            ['n'=>2,'judul_default'=>'Laporan Online',  'desc_default'=>'Kondisi kendaraan bisa diketahui dari laporan inspeksi online dengan detail dan lengkap.'],
            ['n'=>3,'judul_default'=>'Profesional',     'desc_default'=>'Tim inspektor kami ahli dan berpengalaman dalam inspeksi kendaraan secara teliti.'],
            ['n'=>4,'judul_default'=>'Transparan',      'desc_default'=>'Semua status inspeksi & tagihan bisa dipantau customer langsung dari akun mereka.'],
        ] as $card)
        @php $n = $card['n']; @endphp
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-3 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h2 class="font-semibold text-sm text-gray-800">Kartu {{ $n }}
                    @if($n === 2)<span class="ml-2 badge-blue text-xs">Ada tombol PDF</span>@endif
                </h2>
            </div>
            <div class="px-5 py-5 space-y-4">

                {{-- Ikon --}}
                <div class="space-y-1">
                    <label class="form-label">Nama Ikon
                        <span class="text-gray-400 font-normal text-xs ml-1">(monitor / file-text / shield-check / eye / dll)</span>
                    </label>
                    <input type="text" name="kenapa_card{{ $n }}_icon"
                           value="{{ old("kenapa_card{$n}_icon", $data["kenapa_card{$n}_icon"] ?? '') }}"
                           class="form-input w-full font-mono text-sm"
                           placeholder="monitor">
                </div>

                {{-- Judul --}}
                <div class="space-y-1">
                    <label class="form-label">Judul Kartu</label>
                    <input type="text" name="kenapa_card{{ $n }}_judul"
                           value="{{ old("kenapa_card{$n}_judul", $data["kenapa_card{$n}_judul"] ?? $card['judul_default']) }}"
                           class="form-input w-full"
                           placeholder="{{ $card['judul_default'] }}">
                </div>

                {{-- Deskripsi --}}
                <div class="space-y-1">
                    <label class="form-label">Deskripsi</label>
                    <textarea name="kenapa_card{{ $n }}_desc" rows="2"
                              class="form-input w-full"
                              placeholder="{{ $card['desc_default'] }}">{{ old("kenapa_card{$n}_desc", $data["kenapa_card{$n}_desc"] ?? $card['desc_default']) }}</textarea>
                </div>

                {{-- Kartu 2: upload PDF --}}
                @if($n === 2)
                <div class="space-y-2 bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <label class="form-label mb-0">File Contoh Laporan (PDF)
                        <span class="text-gray-400 font-normal text-xs ml-1">maks 5 MB</span>
                    </label>

                    @php $pdfPath = $data['kenapa_card2_pdf'] ?? ''; @endphp

                    @if($pdfPath)
                    {{-- File aktif --}}
                    <div class="flex items-center gap-3 text-sm">
                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                  d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <div>
                            <p class="font-medium text-gray-700">{{ basename($pdfPath) }}</p>
                            <a href="{{ Storage::url($pdfPath) }}"
                               target="_blank"
                               class="text-primary-600 hover:underline text-xs">
                                Buka file saat ini ↗
                            </a>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500">Upload file baru di bawah untuk mengganti:</p>
                    @else
                    <p class="text-xs text-amber-600">⚠ Belum ada file PDF. Upload di bawah agar tombol "Lihat Contoh Laporan" berfungsi.</p>
                    @endif

                    <input type="file"
                           name="value_file"
                           accept="application/pdf"
                           class="block w-full text-sm text-gray-500
                                  file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                  file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700
                                  hover:file:bg-primary-100 cursor-pointer">
                    @error('value_file')
                        <p class="form-error">{{ $message }}</p>
                    @enderror
                </div>
                @endif

            </div>
        </div>
        @endforeach

        {{-- ── Tombol simpan ─────────────────────────────────────────── --}}
        <div class="flex items-center justify-between pt-1">
            <a href="{{ route('admin.cms.index') }}" class="btn-secondary">← Kembali</a>
            <button type="submit" class="btn-primary">
                💾 Simpan Perubahan
            </button>
        </div>

    </form>
</div>

@endsection
