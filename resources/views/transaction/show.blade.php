@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.inspector')
@section('page-title', 'Transaksi')
@section('title', 'Transaksi')
@section('content')
<div class="space-y-5 max-w-3xl">
    <div class="flex items-center gap-3">
        <a href="javascript:history.back()" class="text-sm text-gray-500 hover:text-gray-700">← Kembali</a>
        <h2 class="text-lg font-bold text-gray-900">Transaksi Booking {{ $booking->booking_code }}</h2>
    </div>

    {{-- Info --}}
    <div class="card p-4 text-sm grid grid-cols-2 gap-3">
        <div><span class="text-gray-500">Customer:</span> <strong>{{ $booking->user->name }}</strong></div>
        <div><span class="text-gray-500">Kendaraan:</span> {{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
        <div><span class="text-gray-500">Paket:</span> {{ $booking->package->name }}</div>
        <div><span class="text-gray-500">Status Booking:</span> <span class="badge-{{ $booking->status_color }}">{{ $booking->status_label }}</span></div>
    </div>

    @if(!$transaction)
    <div class="card p-5 text-center">
        <p class="text-gray-500 mb-3">Transaksi belum dibuat.</p>
        <form method="POST" action="{{ route('transaction.store', $booking) }}">
            @csrf
            <button class="btn-primary">Buat Transaksi</button>
        </form>
    </div>
    @else
    {{-- Item List --}}
    <div class="card overflow-hidden">
        <div class="flex items-center justify-between px-5 py-3 border-b border-gray-100">
            <h3 class="font-semibold">Rincian Tagihan</h3>
            <span class="text-xs text-mono text-gray-500">{{ $transaction->transaction_code }}</span>
        </div>
        <table class="w-full table-auto">
            <thead><tr><th>Item</th><th>Kategori</th><th>Harga</th><th>Qty</th><th>Subtotal</th><th></th></tr></thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td class="text-sm font-medium">{{ $item->item_name }}</td>
                    <td class="text-xs text-gray-500">{{ $item->category }}</td>
                    <td class="text-sm">Rp {{ number_format($item->price,0,',','.') }}</td>
                    <td class="text-sm">{{ $item->quantity }} {{ $item->unit }}</td>
                    <td class="text-sm font-semibold">{{ $item->formatted_subtotal }}</td>
                    <td>
                        <form method="POST" action="{{ route('transaction.item.remove', [$booking, $item]) }}"
                              onsubmit="return confirm('Hapus item ini?')">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 text-xs">✕</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50">
                    <td colspan="4" class="px-4 py-2 text-right text-sm text-gray-500">Subtotal</td>
                    <td class="px-4 py-2 text-sm">Rp {{ number_format($transaction->subtotal,0,',','.') }}</td>
                    <td></td>
                </tr>
                <tr class="bg-gray-50">
                    <td colspan="4" class="px-4 py-2 text-right text-sm text-gray-500">PPN 11%</td>
                    <td class="px-4 py-2 text-sm">Rp {{ number_format($transaction->tax,0,',','.') }}</td>
                    <td></td>
                </tr>
                <tr class="bg-primary-50">
                    <td colspan="4" class="px-4 py-2 text-right font-bold text-gray-800">Total</td>
                    <td class="px-4 py-2 font-bold text-primary-600 text-lg">{{ $transaction->formatted_total }}</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Tambah Item --}}
    <div class="card p-5">
        <h3 class="font-semibold mb-4">Tambah Item</h3>
        <form method="POST" action="{{ route('transaction.item.add', $booking) }}" class="space-y-3" id="addItemForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="sm:col-span-2">
                    <label class="form-label">Dari Master Tarif (opsional)</label>
                    <select id="tariffSelect" class="form-input text-sm" onchange="fillFromTariff(this)">
                        <option value="">-- Pilih dari tarif --</option>
                        @foreach($tariffs->groupBy('category') as $cat => $items)
                        <optgroup label="{{ $cat }}">
                            @foreach($items as $t)
                            <option value="{{ $t->id }}" data-name="{{ $t->name }}" data-cat="{{ $t->category }}"
                                    data-price="{{ $t->price }}" data-unit="{{ $t->unit }}">
                                {{ $t->name }} — Rp {{ number_format($t->price,0,',','.') }}/{{ $t->unit }}
                            </option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    <input type="hidden" name="tariff_id" id="tariffId">
                </div>
                <div>
                    <label class="form-label">Nama Item</label>
                    <input type="text" name="item_name" id="itemName" class="form-input text-sm" required>
                </div>
                <div>
                    <label class="form-label">Kategori</label>
                    <input type="text" name="category" id="itemCat" class="form-input text-sm" required>
                </div>
                <div>
                    <label class="form-label">Harga (Rp)</label>
                    <input type="number" name="price" id="itemPrice" class="form-input text-sm" min="0" required>
                </div>
                <div class="flex gap-2">
                    <div class="flex-1">
                        <label class="form-label">Jumlah</label>
                        <input type="number" name="quantity" value="1" class="form-input text-sm" min="1" required>
                    </div>
                    <div class="flex-1">
                        <label class="form-label">Satuan</label>
                        <input type="text" name="unit" id="itemUnit" value="pcs" class="form-input text-sm" required>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn-primary btn-sm">+ Tambah Item</button>
        </form>
    </div>

    {{-- Payment --}}
    <div class="card p-5">
        <h3 class="font-semibold mb-3">Status Pembayaran</h3>
        <div class="flex items-center gap-3 mb-4">
            <span class="badge-{{ $transaction->payment_status === 'paid' ? 'green' : ($transaction->payment_status === 'partial' ? 'yellow' : 'red') }} text-sm px-3 py-1">
                {{ $transaction->payment_status_label }}
            </span>
            @if($transaction->paid_at)
            <span class="text-sm text-gray-500">Dibayar: {{ $transaction->paid_at->isoFormat('D MMM Y HH:mm') }}</span>
            @endif
        </div>
        <form method="POST" action="{{ route('transaction.payment', $booking) }}" class="flex flex-wrap gap-3">
            @csrf
            <select name="payment_method" class="form-input text-sm" style="width:180px" required>
                <option value="">-- Metode Bayar --</option>
                @foreach(['Transfer Bank','QRIS','Tunai','Kartu Debit','Kartu Kredit'] as $m)
                <option value="{{ $m }}" {{ $transaction->payment_method===$m?'selected':'' }}>{{ $m }}</option>
                @endforeach
            </select>
            <select name="payment_status" class="form-input text-sm" style="width:160px">
                <option value="unpaid"  {{ $transaction->payment_status==='unpaid'?'selected':'' }}>Belum Bayar</option>
                <option value="partial" {{ $transaction->payment_status==='partial'?'selected':'' }}>Bayar Sebagian</option>
                <option value="paid"    {{ $transaction->payment_status==='paid'?'selected':'' }}>Lunas</option>
            </select>
            <button class="btn-primary btn-sm">Simpan Pembayaran</button>
        </form>
    </div>
    @endif
</div>

@push('scripts')
<script>
function fillFromTariff(sel) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt.value) return;
    document.getElementById('tariffId').value   = opt.value;
    document.getElementById('itemName').value   = opt.dataset.name;
    document.getElementById('itemCat').value    = opt.dataset.cat;
    document.getElementById('itemPrice').value  = opt.dataset.price;
    document.getElementById('itemUnit').value   = opt.dataset.unit;
}
</script>
@endpush
@endsection
