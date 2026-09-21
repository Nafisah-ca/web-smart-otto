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
                'Paket'         => $booking->package->name,
                'Biaya'         => $booking->package->formatted_price,
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
