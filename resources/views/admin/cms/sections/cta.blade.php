@extends('layouts.admin')
@section('page-title', 'Edit — ' . $meta['label'])
@section('content')

<div class="max-w-2xl space-y-5">

    <nav class="flex items-center gap-2 text-sm text-gray-500">
        <a href="{{ route('admin.cms.index') }}" class="hover:text-gray-700">CMS Konten</a>
        <span>/</span>
        <span class="text-gray-800 font-medium">{{ $meta['label'] }}</span>
    </nav>

    <form method="POST" action="{{ route('admin.cms.save', $section) }}" class="space-y-4">
        @csrf

        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">{{ $meta['label'] }}</h2>
                <p class="text-xs text-gray-500 mt-0.5">{{ $meta['desc'] }}</p>
            </div>

            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Judul</label>
                    <input type="text" name="cta_title" value="{{ old('cta_title', $data['cta_title']) }}"
                           class="form-input w-full" placeholder="Contoh: Siap Booking Inspeksi?">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Subjudul / Deskripsi</label>
                    <textarea name="cta_subtitle" rows="2"
                              class="form-input w-full">{{ old('cta_subtitle', $data['cta_subtitle']) }}</textarea>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Teks Tombol</label>
                    <input type="text" name="cta_button_text" value="{{ old('cta_button_text', $data['cta_button_text']) }}"
                           class="form-input w-full" placeholder="Contoh: Booking Sekarang">
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
