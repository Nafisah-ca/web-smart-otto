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
                    <label class="block text-sm font-medium text-gray-700">Tagline Footer</label>
                    <textarea name="footer_tagline" rows="2"
                              class="form-input w-full">{{ old('footer_tagline', $data['footer_tagline']) }}</textarea>
                    <p class="text-xs text-gray-400">Teks singkat di bawah nama brand pada footer.</p>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Teks Hak Cipta</label>
                    <input type="text" name="footer_copyright" value="{{ old('footer_copyright', $data['footer_copyright']) }}"
                           class="form-input w-full" placeholder="Contoh: © 2026 Smart Otto. Seluruh hak cipta dilindungi.">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">URL Instagram</label>
                    <input type="text" name="footer_social_ig" value="{{ old('footer_social_ig', $data['footer_social_ig']) }}"
                           class="form-input w-full" placeholder="https://instagram.com/...">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">URL Facebook</label>
                    <input type="text" name="footer_social_fb" value="{{ old('footer_social_fb', $data['footer_social_fb']) }}"
                           class="form-input w-full" placeholder="https://facebook.com/...">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Link WhatsApp</label>
                    <input type="text" name="footer_social_wa" value="{{ old('footer_social_wa', $data['footer_social_wa']) }}"
                           class="form-input w-full" placeholder="https://wa.me/628...">
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
