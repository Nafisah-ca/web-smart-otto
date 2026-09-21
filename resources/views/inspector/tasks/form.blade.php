@extends('layouts.inspector')
@section('title', 'Form Inspeksi')
@section('content')
<div class="space-y-5">
    <div class="flex items-center gap-3">
        <a href="{{ route('inspector.tasks.show', $booking) }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
        <h2 class="text-lg font-bold text-gray-900">Form Hasil Inspeksi</h2>
    </div>

    <div class="bg-primary-50 border border-primary-200 rounded-lg p-4 text-sm">
        <strong>{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</strong> — {{ $booking->vehicle->plate_number }}
        · {{ $booking->user->name }} · {{ $booking->package->name }}
    </div>

    <form method="POST" action="{{ $existingResult ? route('inspector.tasks.update', $booking) : route('inspector.tasks.store', $booking) }}"
          class="space-y-5" id="inspectionForm">
        @csrf
        @if($existingResult) @method('PUT') @endif

        {{-- Checklist --}}
        @php $grouped = $checklistItems->groupBy('category'); @endphp
        @foreach($grouped as $category => $items)
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3">{{ $category }}</h3>
            <div class="space-y-3">
                @foreach($items as $idx => $item)
                @php
                    $existing = collect($existingResult?->checklist_json ?? [])->firstWhere('item_id', $item->id);
                    $existingStatus = $existing['status'] ?? 'baik';
                    $existingNote = $existing['note'] ?? '';
                @endphp
                <div class="border border-gray-100 rounded-lg p-3">
                    <input type="hidden" name="checklist[{{ $item->id }}][item_id]" value="{{ $item->id }}">
                    <input type="hidden" name="checklist[{{ $item->id }}][item]" value="{{ $item->item_name }}">
                    <input type="hidden" name="checklist[{{ $item->id }}][category]" value="{{ $item->category }}">
                    <div class="flex items-start justify-between gap-4">
                        <p class="text-sm font-medium text-gray-700 flex-1">{{ $item->item_name }}</p>
                        <div class="flex gap-2 flex-shrink-0">
                            @foreach(['baik'=>'Baik','cukup'=>'Cukup','buruk'=>'Buruk'] as $v=>$l)
                            <label class="cursor-pointer">
                                <input type="radio" name="checklist[{{ $item->id }}][status]" value="{{ $v }}"
                                       {{ $existingStatus === $v ? 'checked' : '' }}
                                       class="sr-only peer" required>
                                <span class="px-2.5 py-1 text-xs rounded-full border peer-checked:font-bold transition-colors
                                    @if($v==='baik') peer-checked:bg-green-100 peer-checked:border-green-500 peer-checked:text-green-700 border-gray-200 text-gray-500
                                    @elseif($v==='cukup') peer-checked:bg-yellow-100 peer-checked:border-yellow-500 peer-checked:text-yellow-700 border-gray-200 text-gray-500
                                    @else peer-checked:bg-red-100 peer-checked:border-red-500 peer-checked:text-red-700 border-gray-200 text-gray-500
                                    @endif">{{ $l }}</span>
                            </label>
                            @endforeach
                        </div>
                    </div>
                    <input type="text" name="checklist[{{ $item->id }}][note]" value="{{ $existingNote }}"
                           placeholder="Catatan (opsional)..." class="form-input text-xs mt-2 py-1.5">
                </div>
                @endforeach
            </div>
        </div>
        @endforeach

        {{-- Kondisi & Rekomendasi --}}
        <div class="card p-5 space-y-4">
            <h3 class="font-semibold text-gray-800">Kesimpulan</h3>
            <div>
                <label class="form-label">Kondisi Keseluruhan Kendaraan</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-2">
                    @foreach(['baik'=>['Baik','green'],'cukup'=>['Cukup','blue'],'perlu_perhatian'=>['Perlu Perhatian','yellow'],'kritis'=>['Kritis','red']] as $k=>[$l,$c])
                    <label class="cursor-pointer">
                        <input type="radio" name="condition_summary" value="{{ $k }}" class="sr-only peer"
                               {{ old('condition_summary', $existingResult?->condition_summary) === $k ? 'checked' : '' }} required>
                        <div class="border-2 rounded-xl p-3 text-center peer-checked:border-{{ $c }}-500 peer-checked:bg-{{ $c }}-50 hover:border-{{ $c }}-300 transition-colors">
                            <p class="text-sm font-medium text-gray-700">{{ $l }}</p>
                        </div>
                    </label>
                    @endforeach
                </div>
                @error('condition_summary')<p class="form-error">{{ $message }}</p>@enderror
            </div>
            <div>
                <label class="form-label">Rekomendasi / Tindakan</label>
                <textarea name="recommendation" rows="3" class="form-input" placeholder="Tuliskan rekomendasi tindakan yang perlu dilakukan...">{{ old('recommendation', $existingResult?->recommendation) }}</textarea>
            </div>
            <div>
                <label class="form-label">Catatan Inspektor</label>
                <textarea name="inspector_notes" rows="2" class="form-input" placeholder="Catatan tambahan dari inspektor...">{{ old('inspector_notes', $existingResult?->inspector_notes) }}</textarea>
            </div>
        </div>

        {{-- Upload Foto --}}
        <div class="card p-5">
            <h3 class="font-semibold text-gray-800 mb-3">Foto Dokumentasi</h3>
            @if($existingResult?->photos)
            <div class="flex flex-wrap gap-2 mb-3">
                @foreach($existingResult->photos as $photo)
                <img src="{{ asset('storage/'.$photo) }}" class="w-24 h-24 object-cover rounded-lg border" alt="foto">
                @endforeach
            </div>
            @endif
            <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 text-center">
                <input type="file" id="photoUpload" accept="image/*" class="hidden">
                <label for="photoUpload" class="cursor-pointer">
                    <p class="text-sm text-gray-500">📸 Klik untuk upload foto</p>
                    <p class="text-xs text-gray-400 mt-1">Maks 5MB per foto</p>
                </label>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="submit" class="btn-primary flex-1 justify-center py-3">
                💾 {{ $existingResult ? 'Perbarui' : 'Simpan' }} Hasil Inspeksi
            </button>
            <a href="{{ route('inspector.tasks.show', $booking) }}" class="btn-secondary px-6">Batal</a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.getElementById('photoUpload')?.addEventListener('change', async function(e) {
    const file = e.target.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
    const resp = await fetch('{{ route("inspector.tasks.photo", $booking) }}', { method: 'POST', body: formData });
    const data = await resp.json();
    if (data.success) alert('Foto berhasil diupload!');
});
</script>
@endpush
@endsection
