@extends('layouts.inspector')
@section('title', 'Detail Tugas')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <a href="{{ route('inspector.tasks.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
        <div class="flex gap-2">
            @if(in_array($booking->status, ['confirmed','waiting','on_progress']))
            <a href="{{ route('inspector.tasks.form', $booking) }}" class="btn-primary btn-sm">🔧 {{ $booking->inspectionResult ? 'Edit Hasil' : 'Isi Hasil Inspeksi' }}</a>
            @endif
            @if($booking->inspectionResult && !$booking->inspectionResult->is_verified && $booking->status === 'on_progress')
            <form method="POST" action="{{ route('inspector.tasks.verify', $booking) }}" onsubmit="return confirm('Selesaikan dan verifikasi inspeksi ini? Laporan hasil & item tambahan akan dikirim ke admin untuk penetapan harga final.')">
                @csrf
                <button class="btn-primary btn-sm bg-green-600 hover:bg-green-700 font-bold shadow-sm flex items-center gap-1.5">
                    ✅ Selesai & Kirim Laporan ke Admin
                </button>
            </form>
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="card p-5 space-y-2">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold">Info Booking</h3>
                <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
            </div>
            <p class="font-mono text-sm text-primary-600 font-bold">{{ $booking->booking_code }}</p>
            <p class="text-sm"><span class="text-gray-500">Customer:</span> {{ $booking->user->name }}</p>
            <p class="text-sm"><span class="text-gray-500">Telepon:</span> {{ $booking->user->phone }}</p>
            <p class="text-sm"><span class="text-gray-500">Tanggal:</span> {{ $booking->booking_date->isoFormat('D MMM Y') }} · {{ substr($booking->booking_time,0,5) }}</p>
            <p class="text-sm"><span class="text-gray-500">Layanan:</span> {{ $booking->package?->name ?? $booking->vehicleCategory?->name ?? 'Inspeksi Kendaraan' }}</p>
            @if($booking->notes)
            <p class="text-sm bg-amber-50 border border-amber-100 rounded p-2"><span class="text-gray-500">Catatan:</span> {{ $booking->notes }}</p>
            @endif
        </div>
        <div class="card p-5 space-y-2">
            <h3 class="font-semibold">Data Kendaraan</h3>
            <p class="text-sm"><span class="text-gray-500">Kendaraan:</span> {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
            <p class="text-sm"><span class="text-gray-500">Plat:</span> {{ $booking->vehicle->plate_number }}</p>
            <p class="text-sm"><span class="text-gray-500">Jenis:</span> {{ ucfirst($booking->vehicle->type) }}</p>
            <p class="text-sm"><span class="text-gray-500">Warna:</span> {{ $booking->vehicle->color ?? '-' }}</p>
        </div>
    </div>

    @if($booking->inspectionResult)
    @php $r = $booking->inspectionResult; @endphp
    <div class="card p-5">
        <div class="flex justify-between items-center mb-3">
            <h3 class="font-semibold">Hasil Inspeksi</h3>
            @if($r->is_verified)
            <span class="badge-green">✅ Terverifikasi & Terkirim ke Admin</span>
            @else
            <span class="badge-yellow">Belum Diverifikasi (Draft)</span>
            @endif
        </div>
        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
            <div>
                <p class="text-gray-500">Kondisi</p>
                <span class="badge-{{ $r->condition_color }}">{{ $r->condition_label }}</span>
            </div>
            <div>
                <p class="text-gray-500">Selesai</p>
                <p>{{ $r->completed_at?->isoFormat('D MMM Y, HH:mm') ?? '-' }}</p>
            </div>
        </div>
        @if($r->recommendation)
        <p class="text-sm text-gray-700 bg-gray-50 p-3 rounded-lg">{{ $r->recommendation }}</p>
        @endif
    </div>
    @endif

    {{-- Item / Jasa Tambahan dari Master Tarif (Laporan Inspektor ke Admin) --}}
    @php
        $trx = $booking->transaction;
        $additionalItems = $trx ? $trx->items->where('category', '!=', 'Inspeksi') : collect();
        $isVerified = $booking->inspectionResult?->is_verified;
    @endphp
    <div class="card p-5 space-y-4 shadow-sm border border-gray-200">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-gray-100 pb-3">
            <div>
                <h3 class="font-bold text-gray-900 text-base flex items-center gap-2">
                    <span>📦</span> Item / Jasa Tambahan (Master Tarif)
                </h3>
                <p class="text-xs text-gray-500 mt-0.5">
                    Catat permintaan customer (seperti ganti ban, oli, suku cadang, dll.) dari Master Tarif untuk dilaporkan ke admin sebelum konfirmasi harga final.
                </p>
            </div>
            <span class="badge-{{ $additionalItems->count() > 0 ? 'blue' : 'gray' }} text-xs font-bold">
                {{ $additionalItems->count() }} Item Tambahan
            </span>
        </div>

        {{-- Tabel Daftar Item Tambahan yang Sudah Ditambahkan --}}
        @if($additionalItems->count() > 0)
        <div class="overflow-x-auto border border-gray-200 rounded-xl">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                        <th class="p-3">Item / Layanan</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3 text-right">Harga Satuan</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Subtotal</th>
                        @if(!$isVerified)
                        <th class="p-3 text-center">Aksi</th>
                        @endif
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($additionalItems as $item)
                    <tr class="hover:bg-gray-50/50">
                        <td class="p-3">
                            <span class="font-bold text-gray-900">{{ $item->item_name }}</span>
                            @if($item->notes)
                            <span class="block text-[11px] text-gray-500 mt-0.5 font-normal">📝 {{ $item->notes }}</span>
                            @endif
                        </td>
                        <td class="p-3">
                            <span class="px-2 py-0.5 text-[10px] rounded-full bg-blue-50 text-blue-700 font-medium border border-blue-200">
                                {{ $item->category }}
                            </span>
                        </td>
                        <td class="p-3 text-right font-medium text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td class="p-3 text-right font-bold text-gray-900">{{ $item->formatted_subtotal }}</td>
                        @if(!$isVerified)
                        <td class="p-3 text-center">
                            <form method="POST" action="{{ route('inspector.tasks.item.remove', [$booking, $item]) }}" onsubmit="return confirm('Hapus item {{ $item->item_name }} dari laporan?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-red-50 text-xs font-semibold" title="Hapus">
                                    ✕ Hapus
                                </button>
                            </form>
                        </td>
                        @endif
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 text-center text-xs text-gray-500">
            ℹ️ Belum ada item tambahan yang dilaporkan. Jika customer meminta ganti ban atau layanan lain dari master tarif, pilih dan tambahkan melalui form di bawah.
        </div>
        @endif

        {{-- Form Tambah Item dari Master Tarif --}}
        @if(!$isVerified)
        <div class="bg-primary-50/50 border border-primary-200 rounded-xl p-4 mt-3">
            <h4 class="font-bold text-primary-900 text-xs uppercase tracking-wider mb-2">+ Tambah Item dari Master Tarif</h4>
            <form method="POST" action="{{ route('inspector.tasks.item.add', $booking) }}" class="space-y-3" id="inspectorAddItemForm">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                    <div class="sm:col-span-2 md:col-span-4">
                        <label class="form-label text-xs font-semibold">Pilih dari Master Tarif (Ganti Ban, Oli, Sparepart, Servis) <span class="text-red-500">*</span></label>
                        <select id="inspectorTariffSelect" class="form-input text-xs" onchange="fillInspectorTariff(this)" required>
                            <option value="">-- Pilih item dari Master Tarif --</option>
                            @foreach($tariffs->groupBy('category') as $cat => $items)
                            <optgroup label="📂 Kategori: {{ $cat }}">
                                @foreach($items as $t)
                                <option value="{{ $t->id }}" data-name="{{ $t->name }}" data-cat="{{ $t->category }}"
                                        data-price="{{ $t->price }}" data-unit="{{ $t->unit }}">
                                    {{ $t->name }} — Rp {{ number_format($t->price,0,',','.') }} / {{ $t->unit }}
                                </option>
                                @endforeach
                            </optgroup>
                            @endforeach
                        </select>
                        <input type="hidden" name="tariff_id" id="insTariffId">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-xs font-semibold">Nama Item <span class="text-red-500">*</span></label>
                        <input type="text" name="item_name" id="insItemName" class="form-input text-xs" required placeholder="Nama suku cadang / layanan">
                    </div>

                    <div>
                        <label class="form-label text-xs font-semibold">Kategori <span class="text-red-500">*</span></label>
                        <input type="text" name="category" id="insItemCat" class="form-input text-xs" required placeholder="Ban / Oli / Sparepart">
                    </div>

                    <div>
                        <label class="form-label text-xs font-semibold">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                        <input type="number" name="price" id="insItemPrice" class="form-input text-xs" min="0" required placeholder="0">
                    </div>

                    <div>
                        <label class="form-label text-xs font-semibold">Jumlah (Qty) <span class="text-red-500">*</span></label>
                        <input type="number" name="quantity" value="1" min="1" class="form-input text-xs" required>
                    </div>

                    <div>
                        <label class="form-label text-xs font-semibold">Satuan <span class="text-red-500">*</span></label>
                        <input type="text" name="unit" id="insItemUnit" value="pcs" class="form-input text-xs" required>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="form-label text-xs font-semibold">Catatan Permintaan Customer (Opsional)</label>
                        <input type="text" name="notes" placeholder="Contoh: Customer minta ganti ban depan kanan" class="form-input text-xs">
                    </div>
                </div>

                <div class="pt-1">
                    <button type="submit" class="btn-primary btn-sm text-xs font-bold shadow-sm">
                        + Tambahkan ke Laporan Admin
                    </button>
                </div>
            </form>
        </div>
        @else
        <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 text-xs text-gray-600 flex items-center gap-2">
            <span>🔒</span>
            <span>Laporan inspeksi telah diverifikasi & dikirim ke admin. Daftar item tambahan telah dikunci untuk konfirmasi harga final oleh admin.</span>
        </div>
        @endif
    </div>

    {{-- Status Transaksi Ringkas --}}
    @if($booking->transaction)
    <div class="card p-4 bg-gray-50 flex items-center justify-between text-xs text-gray-600">
        <div>
            <span>Status Transaksi: </span>
            <span class="badge-{{ $booking->transaction->payment_status_color }} font-bold">
                {{ $booking->transaction->payment_status_label }}
            </span>
            <span class="ml-2 font-mono font-bold text-gray-800">Total: {{ $booking->transaction->formatted_total }}</span>
        </div>
        <span class="text-gray-400">Admin akan mengonfirmasi harga final setelah Anda mengirim laporan inspeksi.</span>
    </div>
    @endif
</div>

@push('scripts')
<script>
function fillInspectorTariff(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    document.getElementById('insTariffId').value   = opt.value;
    document.getElementById('insItemName').value   = opt.dataset.name;
    document.getElementById('insItemCat').value    = opt.dataset.cat;
    document.getElementById('insItemPrice').value  = opt.dataset.price;
    document.getElementById('insItemUnit').value   = opt.dataset.unit;
}
</script>
@endpush
</div>
@endsection
