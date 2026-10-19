@extends('layouts.admin')
@section('page-title', 'Detail Booking')
@section('content')
<div class="space-y-5 max-w-4xl">
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.bookings.index') }}" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
        <div class="flex gap-2">
            {{-- Konfirmasi --}}
            @if($booking->status === 'pending')
            <form method="POST" action="{{ route('admin.bookings.confirm', $booking) }}">
                @csrf
                <button class="btn-primary btn-sm">✅ Konfirmasi</button>
            </form>
            @endif
            {{-- Update Status --}}
            <form method="POST" action="{{ route('admin.bookings.status', $booking) }}" class="flex gap-2">
                @csrf
                <select name="status" class="form-input text-sm py-1.5">
                    @foreach(\App\Models\Booking::$statusLabels as $k=>$v)
                    <option value="{{ $k }}" {{ $booking->status===$k?'selected':'' }}>{{ $v }}</option>
                    @endforeach
                </select>
                <button class="btn-secondary btn-sm">Update</button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        {{-- Info Booking --}}
        <div class="card p-5 space-y-3">
            <div class="flex items-center justify-between">
                <h3 class="font-semibold">Info Booking</h3>
                <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span>
            </div>
            @foreach([
                'Kode Booking'  => $booking->booking_code,
                'Tanggal'       => $booking->booking_date->isoFormat('D MMMM Y'),
                'Jam'           => substr($booking->booking_time,0,5).' WIB',
                'Layanan'       => $booking->package?->name ?? $booking->vehicleCategory?->name ?? 'Inspeksi Kendaraan',
                'Biaya'         => $booking->formatted_price,
                'Catatan'       => $booking->notes ?? '-',
            ] as $label => $value)
            <div class="flex justify-between text-sm border-b border-gray-50 pb-2">
                <span class="text-gray-500">{{ $label }}</span>
                <span class="font-medium text-right">{{ $value }}</span>
            </div>
            @endforeach
        </div>

        {{-- Customer & Kendaraan --}}
        <div class="space-y-4">
            <div class="card p-5 space-y-2">
                <h3 class="font-semibold text-sm text-gray-700">Data Customer</h3>
                <p class="font-medium">{{ $booking->user->name }}</p>
                <p class="text-sm text-gray-500">{{ $booking->user->email }}</p>
                <p class="text-sm text-gray-500">{{ $booking->user->phone }}</p>
            </div>
            <div class="card p-5 space-y-2">
                <h3 class="font-semibold text-sm text-gray-700">Data Kendaraan</h3>
                <p class="font-medium">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
                <p class="text-sm text-gray-500">{{ $booking->vehicle->plate_number }} · {{ ucfirst($booking->vehicle->type) }}</p>
                <p class="text-sm text-gray-500">Warna: {{ $booking->vehicle->color ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Konfirmasi Harga oleh Admin (Alur: hanya di akhir setelah inspeksi selesai) --}}
    @if($booking->transaction?->payment_status === 'price_pending')
    @php
        $cat = $booking->vehicleCategory;
        $trx = $booking->transaction;
        $additionalItems = $trx ? $trx->items->where('category', '!=', 'Inspeksi') : collect();
        $inspectionDone = $booking->inspectionResult && $booking->inspectionResult->is_verified;
    @endphp

    @if(!$inspectionDone)
    {{-- Belum Selesai Inspeksi: Menunggu Laporan Inspektor --}}
    <div class="card p-5 border-2 border-amber-300 bg-amber-50/80 shadow-sm">
        <div class="flex items-start gap-3.5">
            <div class="w-10 h-10 rounded-full bg-amber-200 text-amber-800 flex items-center justify-center font-bold text-lg flex-shrink-0">
                ⏳
            </div>
            <div class="flex-1">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-1">
                    <h3 class="font-bold text-amber-900 text-base">Menunggu Inspeksi Selesai untuk Konfirmasi Harga Final</h3>
                    <span class="badge-yellow text-xs font-bold w-fit">Menunggu Laporan Inspektor</span>
                </div>
                <p class="text-xs text-amber-800 mt-1 leading-relaxed">
                    Sesuai alur, <strong>admin hanya mengonfirmasi harga di akhir</strong> setelah inspektor selesai melakukan inspeksi dan mengirimkan laporan item/jasa tambahan dari Master Tarif (jika ada permintaan customer seperti ganti ban, oli, atau sparepart).
                </p>
                <div class="mt-3 pt-2.5 border-t border-amber-200 flex flex-wrap items-center justify-between gap-2 text-xs text-amber-900">
                    <div>
                        <span>Inspektor Bertugas: </span>
                        <strong>{{ $booking->inspector?->name ?? 'Belum di-assign' }}</strong>
                        @if($booking->status)
                        <span class="ml-1 badge-{{ $booking->status_color }} text-[10px]">{{ $booking->status_label }}</span>
                        @endif
                    </div>
                    @if($additionalItems->count() > 0)
                    <span class="text-primary-700 font-bold bg-amber-100 px-2 py-0.5 rounded-md">
                        📦 {{ $additionalItems->count() }} item tambahan telah dilaporkan inspektor sejauh ini
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @else
    {{-- Inspeksi Selesai: Form Konfirmasi Harga Final di Akhir --}}
    <div class="card p-5 border-2 border-emerald-300 bg-emerald-50/70 shadow-sm space-y-4">
        <div class="flex items-start justify-between">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center font-bold text-lg flex-shrink-0">
                    💰
                </div>
                <div>
                    <h3 class="font-bold text-emerald-900 text-base">Konfirmasi Harga Final di Akhir (Inspeksi Selesai)</h3>
                    <p class="text-xs text-emerald-800 mt-0.5">
                        Inspektor telah menyelesaikan inspeksi kendaraan dan memverifikasi laporan. Tinjau rincian item tambahan di bawah ini lalu tentukan harga final untuk membuka pembayaran bagi customer.
                    </p>
                </div>
            </div>
            <span class="badge-green text-xs font-bold">Siap Dikonfirmasi</span>
        </div>

        {{-- Rincian Item Tambahan dari Laporan Inspektor --}}
        @if($additionalItems->count() > 0)
        <div class="bg-white rounded-xl p-4 border border-emerald-200 shadow-sm space-y-2">
            <div class="flex items-center justify-between pb-1.5 border-b border-gray-100">
                <h4 class="text-xs font-bold text-gray-700 uppercase tracking-wider">Item / Jasa Tambahan dari Master Tarif (Laporan Inspektor):</h4>
                <span class="text-xs font-semibold text-primary-600">{{ $additionalItems->count() }} Item Tambahan</span>
            </div>
            <div class="divide-y divide-gray-100 text-xs">
                @foreach($additionalItems as $item)
                <div class="py-2 flex justify-between items-center">
                    <div>
                        <span class="font-bold text-gray-900">{{ $item->item_name }}</span>
                        <span class="px-2 py-0.5 ml-2 text-[10px] rounded-full bg-blue-50 text-blue-700 border border-blue-200 font-medium">{{ $item->category }}</span>
                        @if($item->notes)
                        <span class="text-gray-500 block text-[11px] mt-0.5">Catatan: {{ $item->notes }}</span>
                        @endif
                    </div>
                    <div class="text-right">
                        <span class="text-gray-500">{{ $item->quantity }} {{ $item->unit }} × Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                        <strong class="block text-gray-900 font-mono">{{ $item->formatted_subtotal }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-white/80 rounded-xl p-3 text-xs text-gray-600 border border-emerald-200 flex items-center gap-2">
            <span>ℹ️</span>
            <span>Inspektor tidak mencatat item tambahan (customer hanya menggunakan layanan inspeksi standar).</span>
        </div>
        @endif

        {{-- Form Submit Konfirmasi Harga --}}
        <form method="POST" action="{{ route('admin.bookings.confirm-price', $booking) }}" class="flex flex-wrap gap-4 items-end pt-3 border-t border-emerald-200">
            @csrf
            <div>
                <label class="form-label text-xs font-bold text-gray-800">Biaya Inspeksi Final (Rp) <span class="text-red-500">*</span></label>
                <div class="relative">
                    <span class="absolute left-3 top-1/2 -translate-y-1/2 text-sm text-gray-500 font-bold">Rp</span>
                    <input type="number" name="final_price"
                           value="{{ old('final_price', $booking->final_price ?? $cat?->price_min ?? '') }}"
                           min="{{ $cat?->price_min ?? 0 }}"
                           max="{{ $cat?->price_max ?? 99999999 }}"
                           step="1000"
                           class="form-input pl-9 w-52 text-sm font-bold @error('final_price') border-red-400 @enderror"
                           required>
                </div>
                @error('final_price')<p class="form-error text-xs mt-1">{{ $message }}</p>@enderror
                @if($cat)
                <p class="text-[11px] text-gray-500 mt-1">Kisaran kategori {{ $cat->name }}: {{ $cat->formatted_range }}</p>
                @endif
            </div>

            <button type="submit" class="btn-primary font-bold shadow-md bg-emerald-600 hover:bg-emerald-700 py-2.5 px-5 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                Konfirmasi Harga Final & Buka Pembayaran
            </button>
        </form>
    </div>
    @endif
    @endif

    {{-- Assign Inspektor --}}
    <div class="card p-5">
        <h3 class="font-semibold mb-3">Assign Inspektor</h3>
        <form method="POST" action="{{ route('admin.bookings.assign', $booking) }}" class="flex gap-3">
            @csrf
            <select name="inspector_id" class="form-input text-sm" style="max-width:280px">
                <option value="">-- Pilih Inspektor --</option>
                @foreach($inspectors as $inspector)
                <option value="{{ $inspector->id }}" {{ $booking->inspector_id === $inspector->id ? 'selected' : '' }}>
                    {{ $inspector->name }}
                </option>
                @endforeach
            </select>
            <button class="btn-primary btn-sm">Assign</button>
        </form>
        @if($booking->inspector)
        <p class="text-sm text-gray-500 mt-2">Inspektor saat ini: <strong>{{ $booking->inspector->name }}</strong></p>
        @endif
    </div>

    {{-- Hasil Inspeksi --}}
    @if($booking->inspectionResult)
    @php $result = $booking->inspectionResult; @endphp
    <div class="card p-5">
        <h3 class="font-semibold mb-3">Hasil Inspeksi</h3>
        <div class="grid grid-cols-2 gap-3 text-sm mb-3">
            <div>
                <p class="text-gray-500">Kondisi</p>
                <span class="badge-{{ $result->condition_color }}">{{ $result->condition_label }}</span>
            </div>
            <div>
                <p class="text-gray-500">Status Verifikasi</p>
                <span class="{{ $result->is_verified ? 'badge-green' : 'badge-yellow' }}">{{ $result->is_verified ? 'Terverifikasi' : 'Belum Diverifikasi' }}</span>
            </div>
        </div>
        @if($result->recommendation)
        <p class="text-sm text-gray-700 bg-gray-50 rounded-lg p-3">{{ $result->recommendation }}</p>
        @endif
    </div>
    @endif

    {{-- Transaksi --}}
    @if($booking->transaction)
    @php $trx = $booking->transaction; @endphp
    <div class="card p-5">
        <div class="flex items-center justify-between mb-3">
            <h3 class="font-semibold">Transaksi</h3>
            <span class="badge-{{ $trx->payment_status === 'paid' ? 'green' : 'red' }}">{{ $trx->payment_status_label }}</span>
        </div>
        <div class="space-y-1.5 text-sm">
            @foreach($trx->items as $item)
            <div class="flex justify-between">
                <span>{{ $item->item_name }} × {{ $item->quantity }}</span>
                <span>{{ $item->formatted_subtotal }}</span>
            </div>
            @endforeach
            <div class="flex justify-between font-bold border-t pt-2">
                <span>Total</span>
                <span>{{ $trx->formatted_total }}</span>
            </div>
        </div>
        <a href="{{ route('transaction.show', $booking) }}" class="btn-secondary btn-sm mt-3">Kelola Transaksi</a>
    </div>
    @else
    @if($booking->status === 'completed')
    <div class="card p-4">
        <p class="text-sm text-gray-600 mb-2">Transaksi belum dibuat.</p>
        <a href="{{ route('transaction.show', $booking) }}" class="btn-primary btn-sm">Buat Transaksi</a>
    </div>
    @endif
    @endif

    {{-- Cancel --}}
    @if(!in_array($booking->status, ['completed','cancelled']))
    <div class="card p-5 border border-red-100">
        <h3 class="font-semibold text-red-700 mb-2">Batalkan Booking</h3>
        <form method="POST" action="{{ route('admin.bookings.cancel', $booking) }}" onsubmit="return confirm('Yakin membatalkan booking ini?')">
            @csrf
            <div class="flex gap-3">
                <input type="text" name="reason" placeholder="Alasan pembatalan..." class="form-input text-sm flex-1">
                <button class="btn-danger btn-sm">Batalkan</button>
            </div>
        </form>
    </div>
    @endif
</div>
@endsection
