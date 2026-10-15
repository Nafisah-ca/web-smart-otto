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
                <h2 class="font-semibold text-gray-800">Slot Jam Inspeksi</h2>
                <p class="text-xs text-gray-500 mt-0.5">
                    Daftar jam yang tersedia untuk booking. Pisahkan dengan koma.
                    Format: <code class="bg-gray-100 px-1 rounded">08:00,09:00,10:00</code>
                </p>
            </div>
            <div class="px-5 py-5 space-y-3">

                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">Jam Tersedia</label>
                    <input type="text" name="booking_slots"
                           value="{{ old('booking_slots', $data['booking_slots'] ?? '08:00,09:00,10:00,11:00,13:00,14:00,15:00,16:00') }}"
                           class="form-input w-full font-mono"
                           placeholder="08:00,09:00,10:00,11:00,13:00,14:00,15:00,16:00">
                    <p class="text-xs text-gray-400">Contoh default: 08:00,09:00,10:00,11:00,13:00,14:00,15:00,16:00</p>
                </div>

                {{-- Preview jam --}}
                <div>
                    <p class="text-xs font-medium text-gray-600 mb-2">Preview slot:</p>
                    <div id="slotPreview" class="flex flex-wrap gap-2"></div>
                </div>

            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 text-sm text-blue-800">
            <p class="font-medium mb-1">Catatan:</p>
            <ul class="space-y-1 text-blue-700 text-xs">
                <li>• Jam yang sudah lewat dari waktu sekarang otomatis tidak ditampilkan saat customer booking hari ini.</li>
                <li>• Pisahkan jam dengan koma, tanpa spasi. Format 24 jam (HH:MM).</li>
                <li>• Jam 12:00 tidak disarankan karena waktu istirahat.</li>
            </ul>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('admin.cms.index') }}" class="btn-secondary btn-sm">Kembali</a>
            <button type="submit" class="btn-primary btn-sm">Simpan Perubahan</button>
        </div>

    </form>
</div>

@endsection

@push('scripts')
<script>
function renderPreview() {
    const input = document.querySelector('input[name="booking_slots"]');
    const preview = document.getElementById('slotPreview');
    if (!input || !preview) return;
    const slots = input.value.split(',').map(s => s.trim()).filter(s => s);
    preview.innerHTML = slots.map(s =>
        `<span class="px-3 py-1.5 rounded-lg border-2 border-gray-200 text-sm font-medium text-gray-700 bg-white">${s}</span>`
    ).join('');
}
document.querySelector('input[name="booking_slots"]')?.addEventListener('input', renderPreview);
document.addEventListener('DOMContentLoaded', renderPreview);
</script>
@endpush
