<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $transaction->transaction_code }} — Smart Otto</title>
    @vite(['resources/css/app.css'])
    <style>
        @media print {
            body { background: white !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
            .no-print { display: none !important; }
            .invoice-box { box-shadow: none !important; border: none !important; padding: 0 !important; margin: 0 !important; max-width: 100% !important; }
        }
    </style>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4 font-sans text-gray-800 antialiased">

    {{-- Top Action Bar (hidden when printing) --}}
    <div class="max-w-3xl mx-auto mb-5 flex items-center justify-between no-print">
        <button type="button" onclick="window.history.back()" class="text-sm font-medium text-gray-600 hover:text-gray-900 flex items-center gap-1.5">
            ← Kembali
        </button>
        <div class="flex items-center gap-2">
            <button type="button" onclick="window.print()" class="btn-primary btn-sm flex items-center gap-1.5 shadow-md">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                Cetak / Simpan PDF
            </button>
        </div>
    </div>

    {{-- Invoice Paper Card --}}
    <div class="invoice-box max-w-3xl mx-auto bg-white rounded-2xl shadow-xl border border-gray-200 p-8 sm:p-10 relative overflow-hidden">
        
        {{-- Status Stamp --}}
        @if($transaction->payment_status === 'paid')
        <div class="absolute top-12 right-8 pointer-events-none opacity-85 rotate-[-12deg]">
            <div class="border-4 border-emerald-600 text-emerald-600 font-black text-2xl sm:text-3xl px-6 py-1.5 rounded-xl uppercase tracking-widest bg-emerald-50/70 shadow-sm">
                LUNAS
            </div>
            <p class="text-[10px] text-emerald-700 text-center font-mono mt-0.5">{{ $transaction->paid_at ? $transaction->paid_at->isoFormat('D MMM Y HH:mm') : '' }}</p>
        </div>
        @elseif($transaction->payment_status === 'pending')
        <div class="absolute top-12 right-8 pointer-events-none opacity-85 rotate-[-12deg]">
            <div class="border-4 border-amber-500 text-amber-600 font-black text-xl sm:text-2xl px-4 py-1.5 rounded-xl uppercase tracking-wider bg-amber-50/70">
                PROSES VERIFIKASI
            </div>
        </div>
        @else
        <div class="absolute top-12 right-8 pointer-events-none opacity-85 rotate-[-12deg]">
            <div class="border-4 border-red-600 text-red-600 font-black text-xl sm:text-2xl px-4 py-1.5 rounded-xl uppercase tracking-wider bg-red-50/70">
                BELUM LUNAS
            </div>
        </div>
        @endif

        {{-- Company Header --}}
        <div class="flex items-start justify-between border-b-2 border-gray-100 pb-6 mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-primary-600 text-white rounded-xl flex items-center justify-center font-extrabold text-xl shadow-sm">
                    SO
                </div>
                <div>
                    <h1 class="text-xl font-bold text-gray-900 tracking-tight">SMART OTTO</h1>
                    <p class="text-xs text-gray-500">Layanan Inspeksi & Cek Kendaraan Profesional</p>
                    <p class="text-xs text-gray-400">Jl. Otomotif No. 88, Jakarta · cs@smartotto.test</p>
                </div>
            </div>
            <div class="text-right">
                <span class="text-xs font-semibold text-primary-600 uppercase tracking-wider block">Faktur / Invoice</span>
                <span class="font-mono font-bold text-gray-900 text-base block">{{ $transaction->transaction_code }}</span>
                <span class="text-xs text-gray-500 block">Tanggal: {{ $transaction->created_at->isoFormat('D MMMM Y') }}</span>
            </div>
        </div>

        {{-- Billed To & Vehicle Info --}}
        <div class="grid grid-cols-2 gap-6 text-xs mb-6">
            <div class="space-y-1">
                <p class="font-semibold text-gray-400 uppercase tracking-wider">Ditagihkan Kepada:</p>
                <p class="font-bold text-gray-900 text-sm">{{ $booking->user->name }}</p>
                <p class="text-gray-600">{{ $booking->user->email }}</p>
                <p class="text-gray-600">{{ $booking->user->phone ?? '-' }}</p>
            </div>
            <div class="space-y-1 text-right sm:text-left">
                <p class="font-semibold text-gray-400 uppercase tracking-wider">Informasi Kendaraan & Layanan:</p>
                <p class="font-bold text-gray-900 text-sm">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }} ({{ $booking->vehicle->year }})</p>
                <p class="text-gray-600 font-mono">No. Polisi: <strong>{{ $booking->vehicle->plate_number }}</strong></p>
                <p class="text-gray-600">Kode Booking: <strong class="font-mono">{{ $booking->booking_code }}</strong></p>
            </div>
        </div>

        {{-- Invoice Table --}}
        <div class="border border-gray-200 rounded-xl overflow-hidden mb-6">
            <table class="w-full text-left text-xs border-collapse">
                <thead>
                    <tr class="bg-gray-50 text-gray-600 font-bold border-b border-gray-200">
                        <th class="p-3">Deskripsi Layanan / Item</th>
                        <th class="p-3">Kategori</th>
                        <th class="p-3 text-right">Harga</th>
                        <th class="p-3 text-center">Qty</th>
                        <th class="p-3 text-right">Subtotal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-gray-700">
                    @foreach($transaction->items as $item)
                    <tr>
                        <td class="p-3 font-medium text-gray-900">{{ $item->item_name }}</td>
                        <td class="p-3 text-gray-500">{{ $item->category }}</td>
                        <td class="p-3 text-right">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td class="p-3 text-center">{{ $item->quantity }} {{ $item->unit }}</td>
                        <td class="p-3 text-right font-semibold text-gray-900">{{ $item->formatted_subtotal }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50 border-t-2 border-gray-200 text-gray-600">
                    <tr>
                        <td colspan="4" class="p-2.5 text-right font-medium">Subtotal</td>
                        <td class="p-2.5 text-right font-semibold text-gray-900">{{ $transaction->formatted_subtotal }}</td>
                    </tr>
                    <tr>
                        <td colspan="4" class="p-2.5 text-right font-medium">PPN 11%</td>
                        <td class="p-2.5 text-right font-semibold text-gray-900">{{ $transaction->formatted_tax }}</td>
                    </tr>
                    @if($transaction->discount > 0)
                    <tr>
                        <td colspan="4" class="p-2.5 text-right font-medium text-green-600">Diskon / Potongan</td>
                        <td class="p-2.5 text-right font-semibold text-green-600">- {{ $transaction->formatted_discount }}</td>
                    </tr>
                    @endif
                    <tr class="bg-primary-50 text-primary-900 font-bold text-sm border-t border-primary-200">
                        <td colspan="4" class="p-3 text-right">TOTAL PEMBAYARAN</td>
                        <td class="p-3 text-right font-extrabold text-primary-600">{{ $transaction->formatted_total }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Payment Details & QRIS Box --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-4 border-t border-gray-100 text-xs">
            <div class="space-y-2">
                <p class="font-bold text-gray-900">Metode & Rincian Pembayaran:</p>
                <p class="text-gray-600">Metode: <strong>{{ $transaction->payment_channel ?? ($transaction->payment_method ?? 'QRIS / Transfer Bank') }}</strong></p>
                @if($transaction->payment_reference)
                <p class="text-gray-600">Referensi / No. Pengirim: <strong>{{ $transaction->payment_reference }}</strong></p>
                @endif
                <p class="text-gray-600">Status: <strong class="uppercase text-{{ $transaction->payment_status_color }}-600">{{ $transaction->payment_status_label }}</strong></p>
                @if($transaction->paid_at)
                <p class="text-gray-600">Waktu Bayar: <strong>{{ $transaction->paid_at->isoFormat('dddd, D MMMM Y HH:mm') }} WIB</strong></p>
                @endif

                <div class="pt-2 text-[11px] text-gray-400">
                    <p>Faktur ini sah dan diterbitkan secara digital oleh sistem Smart Otto.</p>
                </div>
            </div>

            {{-- QRIS Preview thumbnail in invoice --}}
            <div class="flex items-center gap-3 bg-gray-50 p-3 rounded-xl border border-gray-200">
                <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Warung Apdal" class="w-16 h-16 object-contain rounded-lg border border-gray-200 bg-white">
                <div>
                    <p class="font-bold text-gray-900">QRIS Standar Nasional</p>
                    <p class="text-[11px] text-gray-500">Merchant: WARUNG APDAL</p>
                    <p class="text-[11px] text-gray-500">NMID: ID1026603303631</p>
                </div>
            </div>
        </div>

        {{-- Signature Section --}}
        <div class="mt-8 pt-6 border-t border-gray-100 flex justify-between items-end text-xs text-gray-600">
            <div>
                <p class="font-semibold text-gray-800">Smart Otto Customer Service</p>
                <p class="text-gray-400 text-[11px]">Terima kasih atas kepercayaan Anda!</p>
            </div>
            <div class="text-center">
                <p class="text-gray-400 mb-10">Kasir / Admin Bertugas,</p>
                <p class="font-bold text-gray-900 border-t border-gray-300 pt-1 px-4">Smart Otto Auto Care</p>
            </div>
        </div>
    </div>

</body>
</html>
