@extends('layouts.customer')
@section('title', 'Laporan Inspeksi')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <a href="{{ route('customer.history.show', $booking) }}" class="text-gray-400 hover:text-gray-600 text-sm">← Kembali</a>
        <button onclick="window.print()" class="btn-secondary btn-sm">🖨️ Cetak</button>
    </div>

    {{-- Header Laporan --}}
    <div class="card p-6 border-t-4 border-primary-600">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-primary-600 rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold">SO</span>
                </div>
                <div>
                    <p class="font-bold text-gray-900">Smart Otto</p>
                    <p class="text-xs text-gray-500">Laporan Hasil Inspeksi Kendaraan</p>
                </div>
            </div>
            <div class="text-right text-sm">
                <p class="font-mono font-bold text-primary-600">{{ $booking->booking_code }}</p>
                <p class="text-gray-500">{{ $result->completed_at?->isoFormat('D MMMM Y') }}</p>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Data Customer</p>
                <p class="font-semibold">{{ $booking->user->name }}</p>
                <p class="text-gray-600">{{ $booking->user->phone }}</p>
                <p class="text-gray-600">{{ $booking->user->email }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Data Kendaraan</p>
                <p class="font-semibold">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</p>
                <p class="text-gray-600">{{ $booking->vehicle->plate_number }} · {{ $booking->vehicle->year }}</p>
                <p class="text-gray-600">Warna: {{ $booking->vehicle->color ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Paket Inspeksi</p>
                <p class="font-semibold">{{ $booking->package->name }}</p>
            </div>
            <div>
                <p class="text-gray-500 text-xs uppercase tracking-wide mb-1">Inspektor</p>
                <p class="font-semibold">{{ $booking->inspector?->name ?? '-' }}</p>
            </div>
        </div>
    </div>

    {{-- Kondisi & Rekomendasi --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Kesimpulan Inspeksi</h3>
        <div class="flex items-center gap-3 mb-4">
            <span class="text-lg">
                @switch($result->condition_summary)
                    @case('baik')🟢@break
                    @case('cukup')🔵@break
                    @case('perlu_perhatian')🟡@break
                    @case('kritis')🔴@break
                @endswitch
            </span>
            <div>
                <span class="badge-{{ $result->condition_color }} text-sm px-3 py-1">{{ $result->condition_label }}</span>
            </div>
        </div>
        @if($result->recommendation)
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-4">
            <p class="text-sm font-medium text-amber-800 mb-1">📋 Rekomendasi Inspektor</p>
            <p class="text-sm text-amber-700">{{ $result->recommendation }}</p>
        </div>
        @endif
        @if($result->inspector_notes)
        <div class="mt-3 bg-gray-50 border border-gray-200 rounded-lg p-4">
            <p class="text-sm font-medium text-gray-700 mb-1">📝 Catatan Inspektor</p>
            <p class="text-sm text-gray-600">{{ $result->inspector_notes }}</p>
        </div>
        @endif
    </div>

    {{-- Checklist --}}
    @if($result->checklist_json)
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Detail Checklist</h3>
        @php $groups = collect($result->checklist_json)->groupBy(fn($item) => $item['category'] ?? 'Umum'); @endphp
        <div class="space-y-4">
            @foreach($groups as $category => $items)
            <div>
                <h4 class="text-sm font-semibold text-gray-600 mb-2 uppercase tracking-wide">{{ $category }}</h4>
                <div class="space-y-1">
                    @foreach($items as $item)
                    <div class="flex items-center justify-between text-sm py-1.5 border-b border-gray-50">
                        <span class="text-gray-700">{{ $item['item'] ?? $item['item_name'] ?? '-' }}</span>
                        <div class="flex items-center gap-2">
                            @php $status = $item['status'] ?? 'baik'; @endphp
                            <span class="@if($status==='baik') badge-green @elseif($status==='buruk') badge-red @else badge-yellow @endif">
                                {{ ucfirst($status) }}
                            </span>
                            @if(!empty($item['note']))
                            <span class="text-xs text-gray-400">{{ $item['note'] }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    {{-- Tagihan --}}
    @if($booking->transaction)
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Rincian Tagihan</h3>
        @php $trx = $booking->transaction; @endphp
        <div class="space-y-2 text-sm">
            @foreach($trx->items as $item)
            <div class="flex justify-between">
                <span>{{ $item->item_name }} × {{ $item->quantity }} {{ $item->unit }}</span>
                <span class="font-medium">{{ $item->formatted_subtotal }}</span>
            </div>
            @endforeach
            <div class="flex justify-between text-gray-500 border-t pt-2">
                <span>PPN 11%</span>
                <span>Rp {{ number_format($trx->tax, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between font-bold text-lg border-t pt-2">
                <span>Total</span>
                <span class="text-primary-600">{{ $trx->formatted_total }}</span>
            </div>
            <div class="flex justify-between pt-1">
                <span class="text-gray-500">Status</span>
                <span class="badge-{{ $trx->payment_status === 'paid' ? 'green' : 'red' }}">{{ $trx->payment_status_label }}</span>
            </div>
        </div>
    </div>
    @endif

    {{-- Digital Signature --}}
    <div class="card p-5">
        <h3 class="font-semibold text-gray-800 mb-3">Tanda Tangan Customer</h3>
        @if($result->customer_signature)
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 mb-3">
            <img src="{{ $result->customer_signature }}" alt="Tanda Tangan" class="max-h-24 mx-auto">
        </div>
        <p class="text-sm text-green-600 text-center">✅ Ditandatangani pada {{ $result->signed_at?->isoFormat('D MMMM Y, HH:mm') }}</p>
        @else
        <p class="text-sm text-gray-500 mb-4">Laporan ini belum ditandatangani. Silakan tanda tangan di bawah untuk mengesahkan laporan.</p>
        <canvas id="signatureCanvas" class="w-full border-2 border-dashed border-gray-300 rounded-lg bg-white cursor-crosshair" height="160"></canvas>
        <div class="flex gap-3 mt-3">
            <button id="clearSignature" class="btn-secondary btn-sm">🗑️ Hapus</button>
            <button id="saveSignature" class="btn-primary btn-sm">💾 Simpan Tanda Tangan</button>
        </div>
        <form id="signatureForm" method="POST" action="{{ route('customer.history.sign', $booking) }}" class="hidden">
            @csrf
            <input type="hidden" name="signature" id="signatureData">
        </form>
        @endif
    </div>
</div>

@push('scripts')
<script>
const canvas = document.getElementById('signatureCanvas');
if (canvas) {
    const ctx = canvas.getContext('2d');
    canvas.width = canvas.offsetWidth;
    let isDrawing = false, lastX = 0, lastY = 0;

    const getPos = (e) => {
        const rect = canvas.getBoundingClientRect();
        const touch = e.touches ? e.touches[0] : e;
        return [touch.clientX - rect.left, touch.clientY - rect.top];
    };

    canvas.addEventListener('mousedown', (e) => { isDrawing = true; [lastX, lastY] = getPos(e); });
    canvas.addEventListener('mousemove', (e) => {
        if (!isDrawing) return;
        const [x, y] = getPos(e);
        ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(x, y);
        ctx.strokeStyle = '#1e3a5f'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke();
        [lastX, lastY] = [x, y];
    });
    canvas.addEventListener('mouseup', () => isDrawing = false);
    canvas.addEventListener('mouseleave', () => isDrawing = false);
    canvas.addEventListener('touchstart', (e) => { e.preventDefault(); isDrawing = true; [lastX, lastY] = getPos(e); });
    canvas.addEventListener('touchmove', (e) => {
        e.preventDefault();
        if (!isDrawing) return;
        const [x, y] = getPos(e);
        ctx.beginPath(); ctx.moveTo(lastX, lastY); ctx.lineTo(x, y);
        ctx.strokeStyle = '#1e3a5f'; ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.stroke();
        [lastX, lastY] = [x, y];
    });
    canvas.addEventListener('touchend', () => isDrawing = false);

    document.getElementById('clearSignature').addEventListener('click', () => ctx.clearRect(0, 0, canvas.width, canvas.height));

    document.getElementById('saveSignature').addEventListener('click', () => {
        const data = canvas.toDataURL('image/png');
        document.getElementById('signatureData').value = data;
        document.getElementById('signatureForm').submit();
    });
}
</script>
@endpush
@endsection
