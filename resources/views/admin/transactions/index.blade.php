@extends('layouts.admin')
@section('page-title', 'Daftar Transaksi & Pembayaran')
@section('title', 'Daftar Transaksi')
@section('content')
<div class="space-y-6">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Transaksi</h2>
            <p class="text-sm text-gray-500">Kelola dan verifikasi pembayaran QRIS, transfer bank, dan tunai dari seluruh customer.</p>
        </div>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="card p-5 border-l-4 border-emerald-500">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Omset Lunas</p>
            <p class="text-2xl font-extrabold text-emerald-600 mt-1">Rp {{ number_format($stats['total_income'], 0, ',', '.') }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['paid_count'] }} transaksi telah selesai</p>
        </div>
        <div class="card p-5 border-l-4 border-amber-500 bg-amber-50/40">
            <p class="text-xs font-semibold text-amber-700 uppercase tracking-wider">Menunggu Verifikasi</p>
            <p class="text-2xl font-extrabold text-amber-600 mt-1">{{ $stats['pending_count'] }}</p>
            <p class="text-xs text-amber-600 mt-1">Perlu pengecekan bukti transfer / QRIS</p>
        </div>
        <div class="card p-5 border-l-4 border-rose-500">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Belum Bayar</p>
            <p class="text-2xl font-extrabold text-rose-600 mt-1">{{ $stats['unpaid_count'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Menunggu pembayaran customer</p>
        </div>
        <div class="card p-5 border-l-4 border-blue-500">
            <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Transaksi Lunas</p>
            <p class="text-2xl font-extrabold text-blue-600 mt-1">{{ $stats['paid_count'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Pembayaran terverifikasi</p>
        </div>
    </div>

    {{-- Filter & Search Bar --}}
    <div class="card p-4 space-y-3">
        <div class="flex flex-col md:flex-row items-center justify-between gap-3">
            {{-- Status Tabs --}}
            <div class="flex flex-wrap gap-1.5 w-full md:w-auto">
                @php $currStatus = request('status', ''); @endphp
                <a href="{{ route('admin.transactions.index') }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === '' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semua
                </a>
                <a href="{{ route('admin.transactions.index', ['status' => 'pending']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'pending' ? 'bg-amber-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Menunggu Verifikasi ({{ $stats['pending_count'] }})
                </a>
                <a href="{{ route('admin.transactions.index', ['status' => 'unpaid']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'unpaid' ? 'bg-red-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Belum Bayar ({{ $stats['unpaid_count'] }})
                </a>
                <a href="{{ route('admin.transactions.index', ['status' => 'paid']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'paid' ? 'bg-emerald-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Lunas ({{ $stats['paid_count'] }})
                </a>
                <a href="{{ route('admin.transactions.index', ['status' => 'partial']) }}"
                   class="px-3 py-1.5 text-xs font-semibold rounded-lg transition-colors {{ $currStatus === 'partial' ? 'bg-blue-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bayar Sebagian
                </a>
            </div>

            {{-- Search Form --}}
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="flex items-center gap-2 w-full md:w-auto">
                @if(request('status'))
                <input type="hidden" name="status" value="{{ request('status') }}">
                @endif
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode TRX, booking, customer..." class="form-input text-xs py-1.5 flex-1 md:w-64">
                <button type="submit" class="btn-secondary btn-sm text-xs">Cari</button>
                @if(request('search') || request('status'))
                <a href="{{ route('admin.transactions.index') }}" class="text-xs text-gray-400 hover:text-gray-600">Reset</a>
                @endif
            </form>
        </div>
    </div>

    {{-- Transactions Table --}}
    <div class="card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="p-3.5">Kode Transaksi</th>
                        <th class="p-3.5">Customer</th>
                        <th class="p-3.5">Booking & Kendaraan</th>
                        <th class="p-3.5">Total Biaya</th>
                        <th class="p-3.5">Metode Bayar</th>
                        <th class="p-3.5">Bukti Bayar</th>
                        <th class="p-3.5">Status</th>
                        <th class="p-3.5 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse($transactions as $trx)
                    @php $booking = $trx->booking; @endphp
                    <tr class="hover:bg-gray-50/70 transition-colors">
                        <td class="p-3.5">
                            <span class="font-mono font-bold text-gray-900 block text-xs">{{ $trx->transaction_code }}</span>
                            <span class="text-xs text-gray-400 block">{{ $trx->created_at->isoFormat('D MMM Y HH:mm') }}</span>
                        </td>
                        <td class="p-3.5">
                            <span class="font-medium text-gray-800 block">{{ $booking->user->name }}</span>
                            <span class="text-xs text-gray-400 block">{{ $booking->user->phone ?? $booking->user->email }}</span>
                        </td>
                        <td class="p-3.5">
                            <a href="{{ route('admin.bookings.show', $booking) }}" class="font-mono text-xs text-primary-600 hover:underline block font-semibold">
                                {{ $booking->booking_code }}
                            </a>
                            <span class="text-xs text-gray-600 block">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})</span>
                            <span class="text-xs text-gray-400 block">{{ $booking->package->name }}</span>
                        </td>
                        <td class="p-3.5">
                            <span class="font-bold text-gray-900 block text-sm">{{ $trx->formatted_total }}</span>
                            <span class="text-xs text-gray-400 block">{{ $trx->items->count() }} item</span>
                        </td>
                        <td class="p-3.5">
                            <span class="font-medium text-gray-800 block text-xs">
                                {{ $trx->payment_channel ?? ($trx->payment_method ?? 'Belum ditentukan') }}
                            </span>
                            @if($trx->payment_reference)
                            <span class="text-xs text-gray-400 block">Ref: {{ $trx->payment_reference }}</span>
                            @endif
                            @if($trx->paid_at)
                            <span class="text-xs text-emerald-600 block font-medium">Lunas: {{ $trx->paid_at->isoFormat('D MMM Y') }}</span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            @if($trx->payment_proof)
                            <div class="flex items-center gap-1.5">
                                <img src="{{ $trx->payment_proof_url }}" alt="Bukti" class="w-10 h-10 object-cover rounded-lg border border-gray-200 cursor-pointer hover:opacity-80 transition-opacity" onclick="openAdminProofModal('{{ $trx->payment_proof_url }}')">
                                <button type="button" onclick="openAdminProofModal('{{ $trx->payment_proof_url }}')" class="text-xs text-primary-600 hover:underline font-semibold">
                                    Lihat
                                </button>
                            </div>
                            @else
                            <span class="text-xs text-gray-400 italic">Tidak ada</span>
                            @endif
                        </td>
                        <td class="p-3.5">
                            <span class="badge-{{ $trx->payment_status_color }} text-xs">
                                {{ $trx->payment_status_label }}
                            </span>
                        </td>
                        <td class="p-3.5 text-right space-x-1">
                            <a href="{{ route('transaction.invoice', $booking) }}" target="_blank" class="btn-secondary btn-sm text-xs py-1" title="Cetak Invoice">
                                📄
                            </a>
                            <a href="{{ route('transaction.show', $booking) }}" class="btn-primary btn-sm text-xs py-1 inline-flex items-center gap-1">
                                Kelola →
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="p-8 text-center text-gray-400">
                            Tidak ada transaksi yang sesuai dengan filter pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($transactions->hasPages())
        <div class="p-4 border-t border-gray-100">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>

{{-- Proof Image Modal --}}
<div id="adminProofModal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeAdminProofModal()">
    <div class="relative max-w-xl max-h-[90vh] bg-white rounded-2xl p-3 shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <button type="button" onclick="closeAdminProofModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center font-bold hover:bg-black transition-colors z-10">
            ✕
        </button>
        <img id="adminModalImg" src="" alt="Bukti Pembayaran Customer" class="w-full h-auto max-h-[80vh] object-contain rounded-xl">
    </div>
</div>

@push('scripts')
<script>
function openAdminProofModal(src) {
    document.getElementById('adminModalImg').src = src;
    document.getElementById('adminProofModal').classList.remove('hidden');
}
function closeAdminProofModal() {
    document.getElementById('adminProofModal').classList.add('hidden');
}
</script>
@endpush
@endsection
