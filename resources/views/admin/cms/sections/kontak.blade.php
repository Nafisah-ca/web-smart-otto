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

        {{-- Informasi Kontak --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Informasi Kontak</h2>
            </div>
            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Nomor Telepon / WhatsApp</label>
                    <input type="text" name="site_phone" value="{{ old('site_phone', $data['site_phone']) }}"
                           class="form-input w-full" placeholder="Contoh: 08123456789">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Alamat Email</label>
                    <input type="text" name="site_email" value="{{ old('site_email', $data['site_email']) }}"
                           class="form-input w-full" placeholder="Contoh: info@smartotto.com">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Alamat Lengkap</label>
                    <textarea name="site_address" rows="3"
                              class="form-input w-full" placeholder="Jalan, kelurahan, kota...">{{ old('site_address', $data['site_address']) }}</textarea>
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">URL Embed Google Maps</label>
                    <input type="text" name="site_maps_embed" value="{{ old('site_maps_embed', $data['site_maps_embed']) }}"
                           class="form-input w-full" placeholder="https://www.google.com/maps/embed?...">
                    <p class="text-xs text-gray-400">Dapatkan dari Google Maps &gt; Bagikan &gt; Sematkan Peta &gt; salin atribut src saja.</p>
                </div>

            </div>
        </div>

        {{-- Jam Operasional --}}
        <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 bg-gray-50">
                <h2 class="font-semibold text-gray-800">Jam Operasional</h2>
            </div>
            <div class="divide-y divide-gray-100">

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Senin – Jumat</label>
                    <input type="text" name="ops_weekday" value="{{ old('ops_weekday', $data['ops_weekday']) }}"
                           class="form-input w-full" placeholder="Contoh: Senin - Jumat: 08.00 - 17.00 WIB">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Sabtu</label>
                    <input type="text" name="ops_saturday" value="{{ old('ops_saturday', $data['ops_saturday']) }}"
                           class="form-input w-full" placeholder="Contoh: Sabtu: 08.00 - 15.00 WIB">
                </div>

                <div class="px-5 py-4 space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Minggu</label>
                    <input type="text" name="ops_sunday" value="{{ old('ops_sunday', $data['ops_sunday']) }}"
                           class="form-input w-full" placeholder="Contoh: Minggu: Tutup">
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
