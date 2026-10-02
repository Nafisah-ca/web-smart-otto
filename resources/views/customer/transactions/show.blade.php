@extends('layouts.customer')
@section('title', 'Pembayaran & Tagihan ' . $transaction->transaction_code)
@section('content')
<div class="space-y-6 max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div class="flex items-center gap-3">
            <a href="{{ route('customer.transactions.index') }}" class="text-sm text-gray-500 hover:text-gray-700 font-medium">← Kembali</a>
            <span class="text-gray-300">|</span>
            <h2 class="text-xl font-bold text-gray-900">Tagihan Pembayaran</h2>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('customer.transactions.invoice', $booking) }}" target="_blank" class="btn-secondary btn-sm flex items-center gap-1.5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak Invoice
            </a>
            <a href="{{ route('customer.history.show', $booking) }}" class="btn-secondary btn-sm">
                Detail Booking
            </a>
        </div>
    </div>

    {{-- Status Alert Banner --}}
    @if($transaction->payment_status === 'paid')
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200 rounded-xl p-5 text-emerald-900 flex items-start gap-4">
        <div class="w-10 h-10 bg-emerald-500 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
            ✓
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-base text-emerald-800">Pembayaran Lunas!</h3>
            <p class="text-sm text-emerald-700 mt-0.5">
                Transaksi ini telah diverifikasi dan lunas pada {{ $transaction->paid_at ? $transaction->paid_at->isoFormat('D MMMM Y HH:mm') : 'hari ini' }} melalui metode <strong>{{ $transaction->payment_channel ?? ($transaction->payment_method ?? 'QRIS / Transfer') }}</strong>.
            </p>
        </div>
    </div>
    @elseif($transaction->payment_status === 'pending')
    <div class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-amber-200 rounded-xl p-5 text-amber-900 flex items-start gap-4">
        <div class="w-10 h-10 bg-amber-500 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold animate-pulse">
            ⏳
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-base text-amber-800">Bukti Pembayaran Sedang Diverifikasi Admin</h3>
            <p class="text-sm text-amber-700 mt-0.5">
                Terima kasih! Bukti pembayaran via <strong>{{ $transaction->payment_channel ?? $transaction->payment_method }}</strong> telah diterima. Admin kami sedang memverifikasi transaksi Anda dalam kurun waktu 10-30 menit.
            </p>
            @if($transaction->payment_proof)
            <div class="mt-3">
                <button type="button" onclick="openProofModal('{{ $transaction->payment_proof_url }}')" class="text-xs bg-amber-200 hover:bg-amber-300 text-amber-900 font-semibold px-3 py-1.5 rounded-lg inline-flex items-center gap-1">
                    🔍 Lihat Bukti yang Telah Diupload
                </button>
            </div>
            @endif
        </div>
    </div>
    @elseif($transaction->payment_status === 'rejected')
    <div class="bg-gradient-to-r from-red-50 to-rose-50 border border-red-200 rounded-xl p-5 text-red-900 flex items-start gap-4">
        <div class="w-10 h-10 bg-red-500 text-white rounded-full flex items-center justify-center flex-shrink-0 text-xl font-bold">
            ✕
        </div>
        <div class="flex-1">
            <h3 class="font-bold text-base text-red-800">Bukti Pembayaran Belum Sesuai</h3>
            <p class="text-sm text-red-700 mt-0.5">
                Catatan Admin: <strong>{{ $transaction->admin_notes ?? 'Bukti pembayaran tidak terbaca atau nominal tidak sesuai.' }}</strong>
            </p>
            <p class="text-xs text-red-600 mt-1">Silakan lakukan pembayaran ulang atau upload bukti transfer yang jelas melalui form di bawah.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Kolom Kiri: Rincian Tagihan & Informasi Booking --}}
        <div class="lg:col-span-5 space-y-5">
            {{-- Info Transaksi --}}
            <div class="card p-5 space-y-4">
                <div class="flex items-center justify-between pb-3 border-b border-gray-100">
                    <div>
                        <span class="text-xs text-gray-400 block font-medium">No. Transaksi</span>
                        <span class="font-mono font-bold text-gray-900 text-base">{{ $transaction->transaction_code }}</span>
                    </div>
                    <span class="badge-{{ $transaction->payment_status_color }}">
                        {{ $transaction->payment_status_label }}
                    </span>
                </div>

                <div class="text-xs space-y-2 text-gray-600">
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kode Booking:</span>
                        <span class="font-mono font-semibold text-gray-800">{{ $booking->booking_code }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Jadwal Inspeksi:</span>
                        <span class="font-medium text-gray-800">{{ $booking->booking_date->isoFormat('D MMM Y') }} · {{ substr($booking->booking_time,0,5) }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Kendaraan:</span>
                        <span class="font-medium text-gray-800">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->plate_number }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-400">Paket:</span>
                        <span class="font-medium text-gray-800">{{ $booking->package->name }}</span>
                    </div>
                </div>

                {{-- Itemized bill --}}
                <div class="pt-3 border-t border-gray-100">
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Rincian Biaya</h4>
                    <div class="space-y-2 text-sm">
                        @foreach($transaction->items as $item)
                        <div class="flex justify-between items-start text-xs">
                            <div>
                                <span class="font-medium text-gray-800">{{ $item->item_name }}</span>
                                <span class="text-gray-400 block">{{ $item->quantity }} {{ $item->unit }} @ Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                            </div>
                            <span class="font-semibold text-gray-800">{{ $item->formatted_subtotal }}</span>
                        </div>
                        @endforeach

                        <div class="border-t border-gray-100 pt-2 space-y-1 text-xs text-gray-500">
                            <div class="flex justify-between">
                                <span>Subtotal</span>
                                <span>{{ $transaction->formatted_subtotal }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>PPN 11%</span>
                                <span>{{ $transaction->formatted_tax }}</span>
                            </div>
                            @if($transaction->discount > 0)
                            <div class="flex justify-between text-green-600">
                                <span>Diskon / Potongan</span>
                                <span>- {{ $transaction->formatted_discount }}</span>
                            </div>
                            @endif
                        </div>

                        <div class="flex justify-between items-center border-t-2 border-primary-500 pt-2 bg-primary-50 p-3 rounded-lg mt-2">
                            <span class="font-bold text-gray-900 text-sm">Total Tagihan</span>
                            <span class="font-extrabold text-primary-600 text-lg">{{ $transaction->formatted_total }}</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Bantuan & CS --}}
            <div class="card p-4 bg-gray-50 text-xs text-gray-600 space-y-2">
                <div class="flex items-center gap-2 text-gray-800 font-semibold">
                    <svg class="w-4 h-4 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Butuh Bantuan Pembayaran?
                </div>
                <p>Jika mengalami kendala transfer atau verifikasi pembayaran, hubungi customer service WhatsApp kami di <strong>0812-3456-7890</strong>.</p>
            </div>
        </div>

        {{-- Kolom Kanan: Pilihan Metode Pembayaran & Form Upload Bukti --}}
        <div class="lg:col-span-7 space-y-6">
            {{-- Payment Channel Selector --}}
            <div class="card overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-white">
                    <h3 class="font-bold text-gray-900 text-base">Pilih Metode Pembayaran</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pilih salah satu metode pembayaran di bawah untuk menyelesaikan transaksi.</p>
                </div>

                {{-- Tabs Nav --}}
                <div class="flex border-b border-gray-200 bg-gray-50 px-3 pt-2 gap-1 overflow-x-auto">
                    <button type="button" onclick="switchTab('qris')" id="tab-btn-qris"
                            class="tab-btn px-4 py-2.5 text-xs font-bold rounded-t-lg transition-all border-b-2 flex items-center gap-1.5 border-primary-600 text-primary-600 bg-white shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-red-500 inline-block animate-ping"></span>
                        ⚡ QRIS (Semua Pembayaran)
                    </button>
                    <button type="button" onclick="switchTab('bank')" id="tab-btn-bank"
                            class="tab-btn px-4 py-2.5 text-xs font-semibold rounded-t-lg transition-all text-gray-600 hover:text-gray-900">
                        🏦 Transfer Bank
                    </button>
                    <button type="button" onclick="switchTab('ewallet')" id="tab-btn-ewallet"
                            class="tab-btn px-4 py-2.5 text-xs font-semibold rounded-t-lg transition-all text-gray-600 hover:text-gray-900">
                        📱 E-Wallet
                    </button>
                    <button type="button" onclick="switchTab('cash')" id="tab-btn-cash"
                            class="tab-btn px-4 py-2.5 text-xs font-semibold rounded-t-lg transition-all text-gray-600 hover:text-gray-900">
                        💵 Tunai / EDC
                    </button>
                </div>

                {{-- Tab Contents --}}
                <div class="p-5 bg-white">
                    {{-- 1. QRIS TAB --}}
                    <div id="tab-content-qris" class="tab-pane space-y-4">
                        <div class="bg-gradient-to-br from-red-50 via-white to-gray-50 border border-red-100 rounded-2xl p-5 text-center shadow-sm">
                            <div class="inline-flex items-center gap-2 bg-red-600 text-white text-xs font-bold px-3 py-1 rounded-full uppercase tracking-wider mb-3">
                                QRIS Standar Pembayaran Nasional
                            </div>

                            <p class="text-sm font-semibold text-gray-800">Scan QR Code dengan Aplikasi E-Wallet / Mobile Banking Apapun</p>
                            <p class="text-xs text-gray-500 mb-4">GoPay · OVO · DANA · ShopeePay · BCA Mobile · Livin Mandiri · BRImo · BNI</p>

                            {{-- QR Image Frame --}}
                            <div class="bg-white p-3 rounded-2xl shadow-md inline-block border-2 border-gray-100 max-w-xs mx-auto">
                                <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Warung Apdal" class="w-64 sm:w-72 h-auto mx-auto rounded-xl object-contain cursor-pointer hover:scale-105 transition-transform" onclick="openProofModal('{{ asset('images/qris.jpg') }}')">
                            </div>

                            <div class="mt-4 flex flex-wrap justify-center gap-2">
                                <a href="{{ asset('images/qris.jpg') }}" download="QRIS-SmartOtto-WarungApdal.jpg" class="btn-secondary btn-sm text-xs inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                    Unduh QRIS
                                </a>
                                <button type="button" onclick="openProofModal('{{ asset('images/qris.jpg') }}')" class="btn-secondary btn-sm text-xs inline-flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v6m3-3H7"/></svg>
                                    Perbesar QR Code
                                </button>
                            </div>

                            {{-- Nominal Helper --}}
                            <div class="mt-4 bg-white border border-gray-200 rounded-xl p-3 max-w-sm mx-auto flex items-center justify-between text-left">
                                <div>
                                    <span class="text-xs text-gray-400 block">Nominal yang harus dibayar:</span>
                                    <span class="font-extrabold text-primary-600 text-base">{{ $transaction->formatted_total }}</span>
                                </div>
                                <button type="button" onclick="copyText('{{ (int)$transaction->total }}', 'Nominal berhasil disalin!')" class="text-xs bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg font-medium transition-colors">
                                    Salin
                                </button>
                            </div>
                        </div>

                        {{-- Panduan Bayar QRIS --}}
                        <div class="bg-gray-50 rounded-xl p-4 text-xs text-gray-600 space-y-2">
                            <p class="font-bold text-gray-800 text-sm">Cara Membayar via QRIS:</p>
                            <ol class="list-decimal list-inside space-y-1 text-gray-600">
                                <li>Buka aplikasi Mobile Banking (BCA, Mandiri, BRI, BNI) atau E-Wallet (GoPay, OVO, DANA, ShopeePay) favorit Anda.</li>
                                <li>Pilih menu <strong>Bayar / Scan QR</strong> pada aplikasi.</li>
                                <li>Arahkan kamera smartphone ke kode QRIS di atas.</li>
                                <li>Pastikan nama penerima/merchant adalah <strong>WARUNG APDAL / Smart Otto</strong>.</li>
                                <li>Masukkan jumlah tagihan tepat sebesar <strong>{{ $transaction->formatted_total }}</strong> dan selesaikan transaksi.</li>
                                <li>Simpan tangkapan layar (screenshot) bukti pembayaran dan upload pada form konfirmasi di bawah.</li>
                            </ol>
                        </div>
                    </div>

                    {{-- 2. BANK TRANSFER TAB --}}
                    <div id="tab-content-bank" class="tab-pane hidden space-y-3">
                        <p class="text-xs text-gray-500 mb-2">Silakan transfer tepat sebesar <strong class="text-primary-600">{{ $transaction->formatted_total }}</strong> ke salah satu rekening bank resmi kami berikut:</p>

                        @foreach(config('payment.bank_transfers', []) as $bank)
                        <div class="border border-gray-200 rounded-xl p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:border-primary-400 transition-colors bg-white">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-10 rounded-lg flex items-center justify-center font-extrabold text-white text-xs shadow-sm" style="background-color: {{ $bank['color'] }};">
                                    {{ $bank['badge'] }}
                                </div>
                                <div>
                                    <h4 class="font-bold text-gray-900 text-sm">{{ $bank['bank_name'] }}</h4>
                                    <p class="font-mono text-base font-bold text-gray-800 tracking-wider select-all">{{ $bank['account_number'] }}</p>
                                    <p class="text-xs text-gray-500">a.n. {{ $bank['account_name'] }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="copyText('{{ $bank['account_number'] }}', 'No. Rekening {{ $bank['bank_name'] }} berhasil disalin!')"
                                    class="btn-secondary btn-sm text-xs self-start sm:self-center">
                                📋 Salin Rekening
                            </button>
                        </div>
                        @endforeach
                    </div>

                    {{-- 3. EWALLET TAB --}}
                    <div id="tab-content-ewallet" class="tab-pane hidden space-y-3">
                        <p class="text-xs text-gray-500 mb-2">Kirim saldo e-wallet ke nomor admin kami:</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach(config('payment.ewallets', []) as $ewallet)
                            <div class="border border-gray-200 rounded-xl p-4 flex items-center justify-between gap-3 bg-white">
                                <div>
                                    <span class="text-xs font-bold text-primary-600 block">{{ $ewallet['name'] }}</span>
                                    <p class="font-mono text-base font-bold text-gray-900 select-all">{{ $ewallet['phone'] }}</p>
                                    <p class="text-xs text-gray-500">a.n. {{ $ewallet['account_name'] }}</p>
                                </div>
                                <button type="button" onclick="copyText('{{ $ewallet['phone'] }}', 'Nomor {{ $ewallet['name'] }} berhasil disalin!')" class="btn-secondary btn-sm text-xs">
                                    Salin
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- 4. CASH / EDC TAB --}}
                    <div id="tab-content-cash" class="tab-pane hidden space-y-4">
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <h4 class="font-bold text-gray-900 text-sm mb-1">💵 Pembayaran Tunai di Lokasi</h4>
                            <p class="text-xs text-gray-600">Anda dapat melakukan pembayaran tunai secara langsung di kasir bengkel Smart Otto atau kepada inspektor kami saat inspeksi kendaraan berlangsung.</p>
                        </div>
                        <div class="border border-gray-200 rounded-xl p-4 bg-gray-50">
                            <h4 class="font-bold text-gray-900 text-sm mb-1">💳 Mesin EDC (Kartu Debit / Kredit)</h4>
                            <p class="text-xs text-gray-600">Tersedia mesin EDC untuk kartu Debit/Kredit (BCA, Mandiri, BRI, Visa, Mastercard, GPN) di counter kasir bengkel kami.</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Form Upload Bukti Pembayaran / Konfirmasi --}}
            @if($transaction->payment_status !== 'paid')
            <div class="card p-5 space-y-4">
                <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                    <div class="w-7 h-7 rounded-full bg-primary-100 text-primary-700 flex items-center justify-center font-bold text-xs">
                        ✍️
                    </div>
                    <div>
                        <h3 class="font-bold text-gray-900 text-sm">Formulir Konfirmasi Pembayaran</h3>
                        <p class="text-xs text-gray-500">Upload bukti transfer / QRIS setelah Anda menyelesaikan pembayaran.</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('customer.transactions.pay', $booking) }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="form-label text-xs font-semibold">Metode Pembayaran yang Digunakan <span class="text-red-500">*</span></label>
                        <select name="payment_method" id="formPaymentMethod" class="form-input text-sm" required>
                            <option value="QRIS" {{ ($transaction->payment_method === 'QRIS' || !$transaction->payment_method) ? 'selected' : '' }}>⚡ QRIS (GoPay / OVO / DANA / M-Banking / Warung Apdal)</option>
                            <option value="Transfer Bank BCA" {{ $transaction->payment_method === 'Transfer Bank BCA' ? 'selected' : '' }}>🏦 Transfer Bank BCA</option>
                            <option value="Transfer Bank Mandiri" {{ $transaction->payment_method === 'Transfer Bank Mandiri' ? 'selected' : '' }}>🏦 Transfer Bank Mandiri</option>
                            <option value="Transfer Bank BRI" {{ $transaction->payment_method === 'Transfer Bank BRI' ? 'selected' : '' }}>🏦 Transfer Bank BRI</option>
                            <option value="Transfer Bank BNI" {{ $transaction->payment_method === 'Transfer Bank BNI' ? 'selected' : '' }}>🏦 Transfer Bank BNI</option>
                            <option value="GoPay" {{ $transaction->payment_method === 'GoPay' ? 'selected' : '' }}>📱 E-Wallet GoPay</option>
                            <option value="OVO" {{ $transaction->payment_method === 'OVO' ? 'selected' : '' }}>📱 E-Wallet OVO</option>
                            <option value="DANA" {{ $transaction->payment_method === 'DANA' ? 'selected' : '' }}>📱 E-Wallet DANA</option>
                            <option value="ShopeePay" {{ $transaction->payment_method === 'ShopeePay' ? 'selected' : '' }}>📱 E-Wallet ShopeePay</option>
                            <option value="Tunai / Cash" {{ $transaction->payment_method === 'Tunai / Cash' ? 'selected' : '' }}>💵 Tunai di Kasir / Lokasi</option>
                            <option value="Kartu Debit / Kredit (EDC)" {{ $transaction->payment_method === 'Kartu Debit / Kredit (EDC)' ? 'selected' : '' }}>💳 Kartu Debit / Kredit EDC</option>
                        </select>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <label class="form-label text-xs font-semibold">Nama Pemilik Rekening / Pengirim (Opsional)</label>
                            <input type="text" name="payment_reference" value="{{ old('payment_reference', $transaction->payment_reference ?? auth()->user()->name) }}" placeholder="Contoh: Budi Santoso" class="form-input text-sm">
                        </div>
                        <div>
                            <label class="form-label text-xs font-semibold">Catatan Tambahan (Opsional)</label>
                            <input type="text" name="notes" value="{{ old('notes', $transaction->notes) }}" placeholder="Catatan pembayaran..." class="form-input text-sm">
                        </div>
                    </div>

                    <div>
                        <label class="form-label text-xs font-semibold">Upload Foto / Screenshot Bukti Transfer / Struk QRIS <span class="text-red-500">*</span></label>
                        <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-primary-500 transition-colors bg-gray-50">
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-xs text-gray-600 justify-center">
                                    <label for="payment_proof" class="relative cursor-pointer bg-white rounded-md font-semibold text-primary-600 hover:text-primary-500 focus-within:outline-none px-2 py-0.5 border border-primary-300">
                                        <span>Pilih File Bukti</span>
                                        <input id="payment_proof" name="payment_proof" type="file" accept="image/*" class="sr-only" onchange="previewUpload(this)" {{ $transaction->payment_proof ? '' : 'required' }}>
                                    </label>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, JPEG, WEBP hingga 5MB</p>
                                <p id="fileChosenName" class="text-xs font-bold text-primary-700 mt-2"></p>
                            </div>
                        </div>

                        @if($transaction->payment_proof)
                        <div class="mt-3 flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg p-2.5">
                            <img src="{{ $transaction->payment_proof_url }}" alt="Bukti Terakhir" class="w-12 h-12 object-cover rounded-md border border-gray-200 cursor-pointer" onclick="openProofModal('{{ $transaction->payment_proof_url }}')">
                            <div class="flex-1 min-w-0">
                                <p class="text-xs font-semibold text-gray-800 truncate">Bukti Pembayaran Tersimpan</p>
                                <p class="text-xs text-gray-500">Klik untuk melihat gambar penuh atau pilih file baru di atas untuk mengganti.</p>
                            </div>
                            <button type="button" onclick="openProofModal('{{ $transaction->payment_proof_url }}')" class="btn-secondary btn-sm text-xs">
                                Lihat
                            </button>
                        </div>
                        @endif
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="btn-primary w-full py-3 text-sm font-bold shadow-md hover:shadow-lg flex items-center justify-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Kirim Konfirmasi Pembayaran
                        </button>
                    </div>
                </form>
            </div>
            @endif
        </div>
    </div>
</div>

{{-- Image Zoom Modal --}}
<div id="proofModal" class="fixed inset-0 z-50 hidden bg-black/80 flex items-center justify-center p-4 backdrop-blur-sm" onclick="closeProofModal()">
    <div class="relative max-w-xl max-h-[90vh] bg-white rounded-2xl p-3 shadow-2xl overflow-hidden" onclick="event.stopPropagation()">
        <button type="button" onclick="closeProofModal()" class="absolute top-3 right-3 w-8 h-8 rounded-full bg-black/60 text-white flex items-center justify-center font-bold hover:bg-black transition-colors z-10">
            ✕
        </button>
        <img id="modalImg" src="" alt="Preview Bukti" class="w-full h-auto max-h-[80vh] object-contain rounded-xl">
    </div>
</div>

{{-- Copy Toast Notification --}}
<div id="copyToast" class="fixed bottom-5 right-5 z-50 hidden bg-gray-900 text-white text-xs px-4 py-3 rounded-xl shadow-xl flex items-center gap-2">
    <span>✅</span>
    <span id="copyToastMsg">Berhasil disalin!</span>
</div>

@push('scripts')
<script>
function switchTab(tabKey) {
    document.querySelectorAll('.tab-pane').forEach(el => el.classList.add('hidden'));
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.classList.remove('border-primary-600', 'text-primary-600', 'bg-white', 'shadow-sm', 'font-bold');
        btn.classList.add('text-gray-600', 'font-semibold');
    });

    const activePane = document.getElementById('tab-content-' + tabKey);
    const activeBtn  = document.getElementById('tab-btn-' + tabKey);
    if (activePane) activePane.classList.remove('hidden');
    if (activeBtn) {
        activeBtn.classList.add('border-primary-600', 'text-primary-600', 'bg-white', 'shadow-sm', 'font-bold');
        activeBtn.classList.remove('text-gray-600');
    }

    // sync form payment method select
    const select = document.getElementById('formPaymentMethod');
    if (select) {
        if (tabKey === 'qris') select.value = 'QRIS';
        else if (tabKey === 'bank') select.value = 'Transfer Bank BCA';
        else if (tabKey === 'ewallet') select.value = 'GoPay';
        else if (tabKey === 'cash') select.value = 'Tunai / Cash';
    }
}

function previewUpload(input) {
    if (input.files && input.files[0]) {
        document.getElementById('fileChosenName').textContent = '✓ File dipilih: ' + input.files[0].name;
    }
}

function openProofModal(imgSrc) {
    document.getElementById('modalImg').src = imgSrc;
    document.getElementById('proofModal').classList.remove('hidden');
}

function closeProofModal() {
    document.getElementById('proofModal').classList.add('hidden');
}

function copyText(text, msg) {
    navigator.clipboard.writeText(text).then(() => {
        const toast = document.getElementById('copyToast');
        document.getElementById('copyToastMsg').textContent = msg || 'Berhasil disalin!';
        toast.classList.remove('hidden');
        setTimeout(() => toast.classList.add('hidden'), 2500);
    });
}
</script>
@endpush
@endsection
