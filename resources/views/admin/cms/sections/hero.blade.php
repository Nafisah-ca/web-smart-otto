@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-2xl space-y-5">

    {{-- Breadcrumb --}}
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
                <p class="text-xs text-gray-500 mt-0.5">{{ $meta['desc'] }}</p>
            </div>

            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Judul Utama</label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $data['hero_title']) }}"
                           class="form-input w-full" placeholder="Judul hero section">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Subjudul / Deskripsi</label>
                    <textarea name="hero_subtitle" rows="3"
                              class="form-input w-full" placeholder="Teks deskripsi di bawah judul">{{ old('hero_subtitle', $data['hero_subtitle']) }}</textarea>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Teks Tombol Booking</label>
                    <input type="text" name="hero_cta_text" value="{{ old('hero_cta_text', $data['hero_cta_text']) }}"
                           class="form-input w-full" placeholder="Contoh: Booking Sekarang">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Label Kecil (Badge)</label>
                    <input type="text" name="hero_badge" value="{{ old('hero_badge', $data['hero_badge']) }}"
                           class="form-input w-full" placeholder="Contoh: Teknisi Bersertifikat">
                    <p class="text-xs text-gray-400">Teks kecil yang muncul di atas judul utama.</p>
                </div>

                <div class="px-5 py-4 space-y-2">
                    <label class="block text-sm font-medium text-gray-700">Gambar Background Hero</label>

                    @php $heroImage = $data['hero_image'] ?? ''; @endphp

                    @if($heroImage)
                    <div class="relative w-full rounded-lg overflow-hidden border border-gray-200" style="height:160px">
                        <img src="{{ asset($heroImage) }}"
                             alt="Hero background" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center opacity-0 hover:opacity-100 transition-opacity">
                            <span class="text-white text-xs font-medium">Gambar saat ini</span>
                        </div>
                    </div>
                    <label class="flex items-center gap-2 text-sm text-red-600 cursor-pointer">
                        <input type="checkbox" name="hero_image_remove" value="1"
                               class="rounded border-gray-300 text-red-500">
                        Hapus gambar (kembali ke gradient)
                    </label>
                    @else
                    <div class="w-full h-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-700 rounded-lg border border-gray-200 flex items-center justify-center">
                        <span class="text-gray-400 text-xs">Belum ada gambar — menggunakan gradient default</span>
                    </div>
                    @endif

                    <div class="mt-2">
                        <input type="file" name="hero_image" accept="image/jpeg,image/png,image/webp"
                               class="block w-full text-sm text-gray-500
                                      file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0
                                      file:text-sm file:font-medium file:bg-blue-50 file:text-blue-700
                                      hover:file:bg-blue-100 cursor-pointer">
                        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, WebP. Maks 3MB. Gambar akan ditampilkan sebagai background hero.</p>
                    </div>
                </div>

            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">Kembali</a>
            <button type="submit" class="btn-primary btn-sm">Simpan Perubahan</button>
        </div>

    </form>
</div>

@endsection
