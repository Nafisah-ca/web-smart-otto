@extends(auth()->user()->isAdmin() ? 'layouts.admin' : 'layouts.inspector')
@section('page-title', 'Transaksi Booking ' . $booking->booking_code)
@section('title', 'Kelola Transaksi')
@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ auth()->user()->isAdmin() ? route('admin.transactions.index') : 'javascript:history.back()' }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">← Kembali</a>
            <span class="text-gray-300">|</span>
            <h2 class="text-lg font-bold text-gray-900">Transaksi {{ $booking->booking_code }}</h2>
        </div>
        <div class="flex items-center gap-2">
            @if($transaction)
            <a href="{{ route('transaction.invoice', $booking) }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Invoice / Kuitansi
            </a>
            @endif
            @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.bookings.show', $booking) }}" class="btn-secondary btn-sm">
                Lihat Booking
            </a>
            @endif
        </div>
    </div>

    {{-- Info Card --}}
    <div class="card p-5 text-sm grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 bg-white shadow-sm">
        <div>
            <span class="text-xs text-gray-400 block font-medium">Customer</span>
            <strong class="text-gray-900 block">{{ $booking->user->name }}</strong>
            <span class="text-xs text-gray-500">{{ $booking->user->phone ?? $booking->user->email }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 block font-medium">Kendaraan</span>
            <span class="font-medium text-gray-900">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</span>
            <span class="text-xs text-gray-500 font-mono block">{{ $booking->vehicle->plate_number }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 block font-medium">Paket Inspeksi</span>
            <span class="font-medium text-gray-900">{{ $booking->package->name }}</span>
            <span class="text-xs text-gray-500 block">{{ $booking->booking_date->isoFormat('D MMM Y') }}</span>
        </div>
        <div>
            <span class="text-xs text-gray-400 block font-medium">Status Booking</span>
            <span class="badge-{{ $booking->status_color }} mt-1 inline-block">{{ $booking->status_label }}</span>
        </div>
    </div>

    @if(!$transaction)
    <div class="card p-8 text-center space-y-4">
        <div class="w-16 h-16 bg-primary-50 text-primary-600 rounded-full flex items-center justify-center mx-auto text-2xl font-bold">
            💳
        </div>
        <div>
            <h3 class="font-bold text-gray-900 text-lg">Transaksi Belum Dibuat</h3>
            <p class="text-sm text-gray-500 max-w-md mx-auto mt-1">Buat tagihan otomatis dengan memasukkan biaya paket inspeksi {{ $booking->package->name }}.</p>
        </div>
        <form method="POST" action="{{ route('transaction.store', $booking) }}">
            @csrf
            <button class="btn-primary px-6 py-2.5 shadow-md">Buat Transaksi Sekarang</button>
        </form>
    </div>
    @else

    {{-- Alert Bukti Pembayaran Menunggu Verifikasi --}}
    @if($transaction->payment_status === 'pending')
    <div class="bg-amber-50 border-2 border-amber-300 rounded-2xl p-5 shadow-sm space-y-4">
        <div class="flex items-start justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="w-10 h-10 rounded-full bg-amber-500 text-white flex items-center justify-center font-bold text-lg flex-shrink-0 animate-bounce">
                    ⚡
                </div>
                <div>
                    <h3 class="font-bold text-base text-amber-900">Customer Telah Mengirim Bukti Pembayaran</h3>
                    <p class="text-xs text-amber-800 mt-0.5">
                        Metode: <strong>{{ $transaction->payment_channel ?? $transaction->payment_method }}</strong>
                        @if($transaction->payment_reference) · Pengirim/Ref: <strong>{{ $transaction->payment_reference }}</strong> @endif
                    </p>
                </div>
            </div>
            <span class="badge-yellow text-xs px-3 py-1 font-bold">Menunggu Verifikasi</span>
        </div>

        @if($transaction->payment_proof)
        <div class="flex flex-col sm:flex-row items-center gap-4 bg-white p-3.5 rounded-xl border border-amber-200">
            <img src="{{ $transaction->payment_proof_url }}" alt="Bukti Transfer" class="w-24 h-24 object-cover rounded-lg border border-gray-200 cursor-pointer shadow-sm hover:scale-105 transition-transform" onclick="openProofModal('{{ $transaction->payment_proof_url }}')">
            <div class="flex-1 text-xs text-gray-600">
                <p class="font-semibold text-gray-800 text-sm">Lampiran Bukti Transfer / Struk QRIS</p>
                <p class="text-gray-500 mt-0.5">Klik thumbnail untuk memperbesar dan mencocokkan nominal <strong>{{ $transaction->formatted_total }}</strong> dengan mutasi rekening / dashboard QRIS.</p>
                <button type="button" onclick="openProofModal('{{ $transaction->payment_proof_url }}')" class="text-primary-600 font-semibold mt-1 hover:underline inline-flex items-center gap-1">
                    🔍 Perbesar Gambar Bukti
                </button>
            </div>
            <div class="flex flex-col sm:flex-row gap-2 flex-shrink-0 w-full sm:w-auto">
                <form method="POST" action="{{ route('transaction.confirm', $booking) }}">
                    @csrf
                    <button type="submit" class="btn-primary btn-sm w-full bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2" onclick="return confirm('Konfirmasi bahwa dana sudah masuk dan ubah status transaksi menjadi LUNAS?')">
                        ✓ Konfirmasi Lunas
                    </button>
                </form>
                <button type="button" onclick="toggleRejectForm()" class="btn-secondary btn-sm w-full text-red-600 hover:bg-red-50 font-semibold px-3 py-2 border-red-200">
                    ✕ Tolak Bukti
                </button>
            </div>
        </div>

        {{-- Reject Form --}}
        <div id="rejectFormBox" class="hidden bg-red-50 p-4 rounded-xl border border-red-200">
            <form method="POST" action="{{ route('transaction.reject', $booking) }}" class="space-y-3">
                @csrf
                <label class="form-label text-xs font-bold text-red-800">Alasan Penolakan Bukti Pembayaran:</label>
                <input type="text" name="admin_notes" placeholder="Contoh: Bukti transfer buram / dana belum masuk / nominal kurang" class="form-input text-xs" required>
                <div class="flex gap-2 justify-end">
                    <button type="button" onclick="toggleRejectForm()" class="btn-secondary btn-sm text-xs">Batal</button>
                    <button type="submit" class="btn-danger btn-sm text-xs font-bold">Kirim Penolakan</button>
                </div>
            </form>
        </div>
        @endif
    </div>
    @endif

    {{-- Item List Card --}}
    <div class="card overflow-hidden shadow-sm">
        <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100 bg-gray-50/50">
            <div>
                <h3 class="font-bold text-gray-900 text-base">Rincian Tagihan & Layanan</h3>
                <p class="text-xs text-gray-500">Nomor Transaksi: <span class="font-mono font-bold text-gray-800">{{ $transaction->transaction_code }}</span></p>
            </div>
            <span class="badge-{{ $transaction->payment_status_color }}">
                {{ $transaction->payment_status_label }}
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full table-auto text-left border-collapse">
                <thead>
                    <tr class="bg-gray-50/80 text-xs font-semibold text-gray-500 uppercase border-b border-gray-100">
                        <th class="px-5 py-3">Nama Item / Layanan</th>
                        <th class="px-4 py-3">Kategori</th>
                        <th class="px-4 py-3">Harga Satuan</th>
                        <th class="px-4 py-3">Qty</th>
                        <th class="px-4 py-3">Subtotal</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @foreach($transaction->items as $item)
                    <tr class="hover:bg-gray-50/50">
                        <td class="px-5 py-3.5 font-medium text-gray-900">
                            {{ $item->item_name }}
                            @if($item->notes)
                            <span class="block text-xs text-gray-400 mt-0.5">{{ $item->notes }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 text-xs text-gray-500">{{ $item->category }}</td>
                        <td class="px-4 py-3.5 text-xs font-medium text-gray-700">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="px-4 py-3.5 text-xs text-gray-600">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td class="px-4 py-3.5 text-sm font-bold text-gray-900">{{ $item->formatted_subtotal }}</td>
                        <td class="px-4 py-3.5 text-right">
                            <form method="POST" action="{{ route('transaction.item.remove', [$booking, $item]) }}" onsubmit="return confirm('Hapus item ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:text-red-700 text-xs font-semibold p-1 hover:bg-red-50 rounded">
                                    ✕ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="border-t-2 border-gray-200">
                    <tr class="bg-gray-50/60 text-xs text-gray-600">
                        <td colspan="4" class="px-5 py-2.5 text-right font-medium">Subtotal</td>
                        <td colspan="2" class="px-4 py-2.5 font-semibold text-gray-900">{{ $transaction->formatted_subtotal }}</td>
                    </tr>
                    <tr class="bg-gray-50/60 text-xs text-gray-600">
                        <td colspan="4" class="px-5 py-2.5 text-right font-medium">PPN 11%</td>
                        <td colspan="2" class="px-4 py-2.5 font-semibold text-gray-900">{{ $transaction->formatted_tax }}</td>
                    </tr>
                    <tr class="bg-gray-50/60 text-xs text-gray-600">
                        <td colspan="4" class="px-5 py-2.5 text-right font-medium">
                            <div class="flex items-center justify-end gap-2">
                                <span>Diskon / Potongan (Rp)</span>
                                <button type="button" onclick="document.getElementById('discountForm').classList.toggle('hidden')" class="text-primary-600 text-xs hover:underline">
                                    [Ubah Diskon]
                                </button>
                            </div>
                        </td>
                        <td colspan="2" class="px-4 py-2.5 font-semibold text-green-600">- {{ $transaction->formatted_discount }}</td>
                    </tr>
                    <tr class="bg-primary-50/70 border-t border-primary-200">
                        <td colspan="4" class="px-5 py-3 text-right font-bold text-gray-900 text-sm">TOTAL TAGIHAN</td>
                        <td colspan="2" class="px-4 py-3 font-extrabold text-primary-600 text-lg">{{ $transaction->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Form Edit Diskon Popup --}}
        <div id="discountForm" class="hidden p-4 bg-gray-100 border-t border-gray-200">
            <form method="POST" action="{{ route('transaction.discount', $booking) }}" class="flex items-center gap-3">
                @csrf
                <label class="text-xs font-bold text-gray-700">Nominal Diskon:</label>
                <input type="number" name="discount" value="{{ (int)$transaction->discount }}" min="0" class="form-input text-xs w-40" required>
                <button type="submit" class="btn-primary btn-sm text-xs">Simpan Diskon</button>
                <button type="button" onclick="document.getElementById('discountForm').classList.add('hidden')" class="btn-secondary btn-sm text-xs">Tutup</button>
            </form>
        </div>
    </div>

    {{-- Tambah Item Transaksi --}}
    <div class="card p-5 shadow-sm">
        <h3 class="font-bold text-gray-900 text-sm mb-3">+ Tambah Item / Jasa Tambahan</h3>
        <form method="POST" action="{{ route('transaction.item.add', $booking) }}" class="space-y-3" id="addItemForm">
            @csrf
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-3">
                <div class="sm:col-span-2 md:col-span-4">
                    <label class="form-label text-xs">Pilih dari Master Tarif (Opsional)</label>
                    <select id="tariffSelect" class="form-input text-xs" onchange="fillFromTariff(this)">
                        <option value="">-- Pilih tarif otomatis --</option>
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
                <div class="sm:col-span-2">
                    <label class="form-label text-xs">Nama Item / Jasa <span class="text-red-500">*</span></label>
                    <input type="text" name="item_name" id="itemName" class="form-input text-xs" required placeholder="Contoh: Penggantian Oli Mesin">
                </div>
                <div>
                    <label class="form-label text-xs">Kategori <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="itemCat" class="form-input text-xs" required placeholder="Contoh: Servis / Sparepart">
                </div>
                <div>
                    <label class="form-label text-xs">Harga Satuan (Rp) <span class="text-red-500">*</span></label>
                    <input type="number" name="price" id="itemPrice" class="form-input text-xs" min="0" required placeholder="0">
                </div>
                <div>
                    <label class="form-label text-xs">Jumlah <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" value="1" class="form-input text-xs" min="1" required>
                </div>
                <div>
                    <label class="form-label text-xs">Satuan <span class="text-red-500">*</span></label>
                    <input type="text" name="unit" id="itemUnit" value="pcs" class="form-input text-xs" required>
                </div>
                <div class="sm:col-span-2">
                    <label class="form-label text-xs">Catatan (Opsional)</label>
                    <input type="text" name="notes" placeholder="Catatan item..." class="form-input text-xs">
                </div>
            </div>
            <div class="pt-2">
                <button type="submit" class="btn-primary btn-sm text-xs font-bold">+ Tambah ke Tagihan</button>
            </div>
        </form>
    </div>

    {{-- Status & Rekam Pembayaran Admin --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        {{-- Card Update Status & Metode Bayar --}}
        <div class="card p-5 space-y-4 shadow-sm">
            <h3 class="font-bold text-gray-900 text-sm">Status & Rekam Pembayaran</h3>

            <div class="flex items-center gap-3 p-3 bg-gray-50 rounded-xl border border-gray-200">
                <span class="badge-{{ $transaction->payment_status_color }} text-xs px-3 py-1 font-bold">
                    {{ $transaction->payment_status_label }}
                </span>
                @if($transaction->paid_at)
                <span class="text-xs text-gray-600">Lunas: <strong>{{ $transaction->paid_at->isoFormat('D MMM Y HH:mm') }}</strong></span>
                @endif
            </div>

            <form method="POST" action="{{ route('transaction.payment', $booking) }}" class="space-y-3">
                @csrf
                <div>
                    <label class="form-label text-xs font-semibold">Metode Pembayaran</label>
                    <select name="payment_method" class="form-input text-xs" required>
                        <option value="">-- Pilih Metode Bayar --</option>
                        <option value="QRIS" {{ $transaction->payment_method === 'QRIS' ? 'selected' : '' }}>⚡ QRIS (Warung Apdal / All E-Wallet & M-Banking)</option>
                        <option value="Transfer Bank BCA" {{ $transaction->payment_method === 'Transfer Bank BCA' ? 'selected' : '' }}>🏦 Transfer Bank BCA</option>
                        <option value="Transfer Bank Mandiri" {{ $transaction->payment_method === 'Transfer Bank Mandiri' ? 'selected' : '' }}>🏦 Transfer Bank Mandiri</option>
                        <option value="Transfer Bank BRI" {{ $transaction->payment_method === 'Transfer Bank BRI' ? 'selected' : '' }}>🏦 Transfer Bank BRI</option>
                        <option value="Transfer Bank BNI" {{ $transaction->payment_method === 'Transfer Bank BNI' ? 'selected' : '' }}>🏦 Transfer Bank BNI</option>
                        <option value="GoPay" {{ $transaction->payment_method === 'GoPay' ? 'selected' : '' }}>📱 E-Wallet GoPay</option>
                        <option value="OVO" {{ $transaction->payment_method === 'OVO' ? 'selected' : '' }}>📱 E-Wallet OVO</option>
                        <option value="DANA" {{ $transaction->payment_method === 'DANA' ? 'selected' : '' }}>📱 E-Wallet DANA</option>
                        <option value="ShopeePay" {{ $transaction->payment_method === 'ShopeePay' ? 'selected' : '' }}>📱 E-Wallet ShopeePay</option>
                        <option value="Tunai / Cash" {{ $transaction->payment_method === 'Tunai / Cash' ? 'selected' : '' }}>💵 Tunai di Kasir / Lokasi</option>
                        <option value="Kartu Debit / Kredit (EDC)" {{ $transaction->payment_method === 'Kartu Debit / Kredit (EDC)' ? 'selected' : '' }}>💳 Kartu Debit / Kredit (Mesin EDC)</option>
                    </select>
                </div>

                <div>
                    <label class="form-label text-xs font-semibold">Status Pembayaran</label>
                    <select name="payment_status" class="form-input text-xs" required>
                        <option value="unpaid"  {{ $transaction->payment_status === 'unpaid' ? 'selected' : '' }}>Belum Bayar</option>
                        <option value="pending" {{ $transaction->payment_status === 'pending' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                        <option value="partial" {{ $transaction->payment_status === 'partial' ? 'selected' : '' }}>Bayar Sebagian</option>
                        <option value="paid"    {{ $transaction->payment_status === 'paid' ? 'selected' : '' }}>LUNAS</option>
                        <option value="rejected"{{ $transaction->payment_status === 'rejected' ? 'selected' : '' }}>Bukti Ditolak</option>
                    </select>
                </div>

                <div>
                    <label class="form-label text-xs font-semibold">Catatan Admin / Verifikasi</label>
                    <input type="text" name="admin_notes" value="{{ $transaction->admin_notes }}" placeholder="Catatan transaksi..." class="form-input text-xs">
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-primary btn-sm w-full text-xs font-bold py-2 shadow-sm">
                        Simpan Pembayaran
                    </button>
                </div>
            </form>
        </div>

        {{-- Card Preview QRIS Warung Apdal untuk Kasir / Lokasi --}}
        <div class="card p-5 space-y-3 bg-gradient-to-br from-red-50/50 via-white to-gray-50 border border-red-100 shadow-sm text-center">
            <div class="flex items-center justify-between pb-2 border-b border-gray-100 text-left">
                <div>
                    <h3 class="font-bold text-gray-900 text-sm">QRIS Kasir Warung Apdal</h3>
                    <p class="text-xs text-gray-500">Tampilkan ke customer untuk scan langsung di kasir.</p>
                </div>
                <span class="badge-red text-xs">NMID: ID1026603303631</span>
            </div>

            <div class="bg-white p-2 rounded-xl border border-gray-200 shadow-sm inline-block max-w-[200px] mx-auto cursor-pointer" onclick="openProofModal('{{ asset('images/qris.jpg') }}')">
                <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Warung Apdal" class="w-44 h-auto mx-auto rounded-lg">
            </div>

            <div class="text-xs text-gray-600">
                <p class="font-bold text-gray-800">Total: {{ $transaction->formatted_total }}</p>
                <p class="text-gray-400">Warung Apdal · Smart Otto</p>
            </div>

            <div class="flex justify-center gap-2 pt-1">
                <button type="button" onclick="openProofModal('{{ asset('images/qris.jpg') }}')" class="btn-secondary btn-sm text-xs">
                    🔍 Perbesar QRIS
                </button>
                <a href="{{ asset('images/qris.jpg') }}" download="QRIS-SmartOtto.jpg" class="btn-secondary btn-sm text-xs">
                    📥 Download
                </a>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Proof Image Modal --}}
<div id="proofModal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeProofModal()">
    <div class="relative max-w-xl max-h-[90vh] bg-white rounded-2xl p-3 shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <button type="button" onclick="closeProofModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center font-bold hover:bg-black transition-colors z-10">
            ✕
        </button>
        <img id="modalImg" src="" alt="Bukti Pembayaran" class="w-full h-auto max-h-[80vh] object-contain rounded-xl">
    </div>
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

function openProofModal(src) {
    document.getElementById('modalImg').src = src;
    document.getElementById('proofModal').classList.remove('hidden');
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
}

function toggleRejectForm() {
    const box = document.getElementById('rejectFormBox');
    box.classList.toggle('hidden');
}
</script>
@endpush
@endsection
