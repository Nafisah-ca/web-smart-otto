@extends('layouts.customer')
@section('title', 'Tagihan & Pembayaran')
@section('content')
<div class="space-y-5">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Tagihan & Pembayaran</h2>
            <p class="text-sm text-gray-500">Kelola dan selesaikan pembayaran inspeksi kendaraan Anda via QRIS, Transfer Bank, atau E-Wallet.</p>
        </div>
        <a href="{{ route('booking.create') }}" class="btn-primary btn-sm">+ Booking Baru</a>
    </div>

    {{-- Filter Status --}}
    <div class="flex flex-wrap gap-2">
        @php $currStatus = request('status', ''); @endphp
        <a href="{{ route('customer.transactions.index') }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === '' ? 'bg-primary-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            Semua
        </a>
        <a href="{{ route('customer.transactions.index', ['status' => 'unpaid']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'unpaid' ? 'bg-red-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            Belum Bayar
        </a>
        <a href="{{ route('customer.transactions.index', ['status' => 'pending']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'pending' ? 'bg-yellow-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            Menunggu Verifikasi
        </a>
        <a href="{{ route('customer.transactions.index', ['status' => 'paid']) }}"
           class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'paid' ? 'bg-green-600 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
            Lunas
        </a>
    </div>

    {{-- Transaction List --}}
    @if($transactions->isEmpty())
    <div class="card p-8 text-center">
        <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3 text-2xl">
            💳
        </div>
        <h3 class="text-base font-semibold text-gray-800 mb-1">Belum Ada Tagihan</h3>
        <p class="text-sm text-gray-500 mb-4">Tagihan pembayaran akan otomatis muncul setelah Anda membuat booking inspeksi kendaraan.</p>
        <a href="{{ route('booking.create') }}" class="btn-primary">Buat Booking Sekarang</a>
    </div>
    @else
    <div class="space-y-4">
        @foreach($transactions as $trx)
        @php $booking = $trx->booking; @endphp
        <div class="card p-5 hover:shadow-md transition-shadow">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-gray-100">
                <div>
                    <div class="flex items-center gap-2">
                        <span class="font-mono font-bold text-gray-900 text-base">{{ $trx->transaction_code }}</span>
                        <span class="badge-{{ $trx->payment_status_color }}">
                            {{ $trx->payment_status_label }}
                        </span>
                    </div>
                    <p class="text-xs text-gray-500 mt-0.5">Booking: <a href="{{ route('customer.history.show', $booking) }}" class="text-primary-600 hover:underline font-mono">{{ $booking->booking_code }}</a> · {{ $trx->created_at->isoFormat('D MMMM Y HH:mm') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Total Pembayaran</p>
                    <p class="text-lg font-bold text-primary-600">{{ $trx->formatted_total }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 py-3 text-sm text-gray-600">
                <div>
                    <span class="text-xs text-gray-400 block">Kendaraan</span>
                    <span class="font-medium text-gray-800">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</span>
                    <span class="text-xs text-gray-500 block font-mono">{{ $booking->vehicle->plate_number }}</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Paket Inspeksi</span>
                    <span class="font-medium text-gray-800">{{ $booking->package->name }}</span>
                    <span class="text-xs text-gray-500 block">{{ $trx->items->count() }} Rincian Item</span>
                </div>
                <div>
                    <span class="text-xs text-gray-400 block">Metode Pembayaran</span>
                    <span class="font-medium text-gray-800">
                        {{ $trx->payment_channel ?? ($trx->payment_method ?? 'Belum Dipilih') }}
                    </span>
                    @if($trx->paid_at)
                    <span class="text-xs text-green-600 block">Dibayar {{ $trx->paid_at->isoFormat('D MMM Y') }}</span>
                    @endif
                </div>
            </div>

            @if($trx->payment_status === 'rejected' && $trx->admin_notes)
            <div class="bg-red-50 border border-red-200 rounded-lg p-3 text-xs text-red-700 mb-3">
                <strong>Catatan Admin:</strong> {{ $trx->admin_notes }}. Silakan upload ulang bukti pembayaran yang valid.
            </div>
            @endif

            <div class="flex flex-wrap items-center justify-between gap-3 pt-3 border-t border-gray-100">
                <div class="text-xs text-gray-500">
                    @if($trx->payment_status === 'pending')
                    <span class="text-yellow-600 font-medium">⏳ Menunggu admin memverifikasi bukti pembayaran Anda.</span>
                    @elseif($trx->payment_status === 'paid')
                    <span class="text-green-600 font-medium">✓ Pembayaran lunas. Terima kasih!</span>
                    @elseif($trx->payment_status === 'unpaid')
                    <span class="text-red-500 font-medium">⚡ Silakan selesaikan pembayaran untuk memproses inspeksi.</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <a href="{{ route('customer.transactions.invoice', $booking) }}" target="_blank" class="btn-secondary btn-sm">
                        📄 Cetak Invoice
                    </a>
                    <a href="{{ route('customer.transactions.show', $booking) }}" class="btn-primary btn-sm">
                        @if($trx->payment_status === 'paid')
                        Lihat Rincian & QRIS
                        @elseif($trx->payment_status === 'pending')
                        Lihat Status & Bukti
                        @else
                        Bayar Sekarang / QRIS →
                        @endif
                    </a>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-4">
        {{ $transactions->links() }}
    </div>
    @endif
</div>
@endsection
