<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Inspeksi - {{ $booking->booking_code }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', 'Helvetica', 'Arial', sans-serif;
            color: #1f2937;
            font-size: 12px;
            line-height: 1.5;
        }
        .page { padding: 0 5px; }
        .header {
            border-top: 4px solid #1e3a5f;
            border-bottom: 1px solid #e5e7eb;
            padding: 18px 20px;
            margin-bottom: 18px;
        }
        .header-table { width: 100%; }
        .header-table td { vertical-align: middle; }
        .logo-box {
            background: #1e3a5f;
            color: #ffffff;
            font-weight: bold;
            width: 38px;
            height: 38px;
            text-align: center;
            font-size: 14px;
            line-height: 38px;
            border-radius: 6px;
        }
        .brand-name { font-weight: bold; font-size: 15px; color: #111827; }
        .brand-sub { font-size: 10px; color: #6b7280; }
        .booking-code { font-family: 'Courier New', monospace; font-weight: bold; color: #1e3a5f; font-size: 13px; }
        .header-right { text-align: right; font-size: 11px; }
        .header-right .muted { color: #6b7280; }

        .section { margin-bottom: 16px; padding: 14px 18px; border: 1px solid #e5e7eb; border-radius: 6px; }
        .section-title { font-weight: bold; color: #374151; font-size: 12px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 10px; padding-bottom: 6px; border-bottom: 1px solid #f3f4f6; }

        .grid { width: 100%; border-collapse: collapse; }
        .grid td { padding: 4px 8px 12px 0; vertical-align: top; width: 50%; }
        .label { color: #6b7280; font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 3px; }
        .value { font-weight: 600; color: #111827; font-size: 11.5px; }
        .value-sub { color: #4b5563; font-size: 11px; }

        .badge {
            display: inline-block;
            padding: 3px 10px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            color: #ffffff;
        }
        .badge-green  { background: #16a34a; }
        .badge-blue   { background: #2563eb; }
        .badge-yellow { background: #d97706; }
        .badge-red    { background: #dc2626; }
        .badge-gray   { background: #6b7280; }

        .condition-row { margin: 4px 0 12px 0; }
        .callout {
            padding: 10px 12px;
            border-radius: 5px;
            margin-top: 8px;
            font-size: 11px;
        }
        .callout-amber { background: #fffbeb; border: 1px solid #fcd34d; color: #92400e; }
        .callout-gray  { background: #f9fafb; border: 1px solid #e5e7eb; color: #4b5563; }
        .callout-title { font-weight: bold; margin-bottom: 3px; }

        table.checklist { width: 100%; border-collapse: collapse; }
        table.checklist th {
            text-align: left;
            font-size: 9.5px;
            text-transform: uppercase;
            color: #6b7280;
            letter-spacing: 0.5px;
            padding: 6px 4px;
            border-bottom: 1px solid #e5e7eb;
        }
        table.checklist td {
            padding: 5px 4px;
            border-bottom: 1px solid #f3f4f6;
            font-size: 11px;
            vertical-align: middle;
        }
        table.checklist td.item-name { color: #374151; }
        table.checklist td.note { color: #9ca3af; font-size: 10px; text-align: right; }
        table.checklist td.status { text-align: right; }
        .group-title {
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin: 12px 0 4px 0;
            padding-top: 8px;
            border-top: 1px solid #f3f4f6;
        }
        .group-title:first-child { border-top: none; padding-top: 0; margin-top: 0; }

        table.invoice { width: 100%; border-collapse: collapse; }
        table.invoice td { padding: 4px 0; font-size: 11px; }
        table.invoice td.amount { text-align: right; font-weight: 500; }
        .inv-divider { border-top: 1px solid #e5e7eb; padding-top: 6px; margin-top: 4px; }
        .inv-total td { font-weight: bold; font-size: 13px; border-top: 1px solid #d1d5db; padding-top: 8px; }
        .inv-total .amount { color: #1e3a5f; }

        .signature-block { margin-top: 14px; text-align: center; }
        .signature-box {
            border: 1px dashed #9ca3af;
            background: #f9fafb;
            padding: 8px;
            border-radius: 6px;
            min-height: 70px;
            margin-bottom: 6px;
        }
        .signature-img { max-height: 70px; }
        .signature-note { font-size: 10px; color: #16a34a; }
        .signature-unsigned { font-size: 10.5px; color: #6b7280; }

        .footer {
            margin-top: 22px;
            padding-top: 10px;
            border-top: 1px solid #e5e7eb;
            font-size: 9.5px;
            color: #9ca3af;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <div class="header">
        <table class="header-table">
            <tr>
                <td style="width: 60%;">
                    <table>
                        <tr>
                            <td style="padding-right: 12px;"><div class="logo-box">SO</div></td>
                            <td>
                                <div class="brand-name">Smart Otto</div>
                                <div class="brand-sub">Laporan Hasil Inspeksi Kendaraan</div>
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="header-right" style="width: 40%;">
                    <div class="booking-code">{{ $booking->booking_code }}</div>
                    <div class="muted">{{ $result->completed_at ? $result->completed_at->isoFormat('D MMMM Y') : '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Data Customer & Kendaraan --}}
    <div class="section">
        <table class="grid">
            <tr>
                <td>
                    <div class="label">Data Customer</div>
                    <div class="value">{{ $booking->user->name }}</div>
                    <div class="value-sub">{{ $booking->user->phone }}</div>
                    <div class="value-sub">{{ $booking->user->email }}</div>
                </td>
                <td>
                    <div class="label">Data Kendaraan</div>
                    <div class="value">{{ $booking->vehicle->brand }} {{ $booking->vehicle->model }}</div>
                    <div class="value-sub">{{ $booking->vehicle->plate_number }} &middot; {{ $booking->vehicle->year }}</div>
                    <div class="value-sub">Warna: {{ $booking->vehicle->color ?? '-' }}</div>
                </td>
            </tr>
            <tr>
                <td>
                    <div class="label">Paket Inspeksi</div>
                    <div class="value">{{ $booking->package->name }}</div>
                </td>
                <td>
                    <div class="label">Inspektor</div>
                    <div class="value">{{ $booking->inspector ? $booking->inspector->name : '-' }}</div>
                </td>
            </tr>
        </table>
    </div>

    {{-- Kesimpulan Inspeksi --}}
    <div class="section">
        <div class="section-title">Kesimpulan Inspeksi</div>
        <div class="condition-row">
            <span class="badge badge-{{ $result->condition_color }}">{{ $result->condition_label }}</span>
        </div>
        @if($result->recommendation)
        <div class="callout callout-amber">
            <div class="callout-title">Rekomendasi Inspektor</div>
            {{ $result->recommendation }}
        </div>
        @endif
        @if($result->inspector_notes)
        <div class="callout callout-gray">
            <div class="callout-title">Catatan Inspektor</div>
            {{ $result->inspector_notes }}
        </div>
        @endif
    </div>

    {{-- Detail Checklist --}}
    @if($result->checklist_json)
    @php
        $legacyMap = \App\Models\InspectionChecklistItem::$legacyStatusMap;
        $statusLabels = [
            'ok'             => 'OK',
            'perlu_diservis' => 'Perlu Diservis',
            'perlu_diganti'  => 'Perlu Diganti',
            'tidak_tersedia' => 'Tidak Tersedia',
        ];
        $statusBadge = [
            'ok'             => 'badge-green',
            'perlu_diservis' => 'badge-yellow',
            'perlu_diganti'  => 'badge-red',
            'tidak_tersedia' => 'badge-gray',
        ];
        $groups = collect($result->checklist_json)->groupBy(fn($item) => $item['category'] ?? 'Umum');
    @endphp
    <div class="section">
        <div class="section-title">Detail Checklist</div>
        <table class="checklist">
            @foreach($groups as $category => $items)
            <tr>
                <td colspan="3"><div class="group-title">{{ $category }}</div></td>
            </tr>
            @foreach($items as $item)
            @php
                $raw    = $item['status'] ?? 'ok';
                $status = $legacyMap[$raw] ?? $raw;
                $label  = $statusLabels[$status] ?? ucfirst($status);
                $badge  = $statusBadge[$status]  ?? 'badge-gray';
            @endphp
            <tr>
                <td class="item-name">{{ $item['item'] ?? $item['item_name'] ?? '-' }}</td>
                <td class="note">{{ !empty($item['note']) ? $item['note'] : '' }}</td>
                <td class="status"><span class="badge {{ $badge }}">{{ $label }}</span></td>
            </tr>
            @endforeach
            @endforeach
        </table>
    </div>
    @endif

    {{-- Rincian Tagihan --}}
    @if($booking->transaction)
    @php $trx = $booking->transaction; @endphp
    <div class="section">
        <div class="section-title">Rincian Tagihan</div>
        <table class="invoice">
            @foreach($trx->items as $item)
            <tr>
                <td>{{ $item->item_name }} &times; {{ $item->quantity }} {{ $item->unit }}</td>
                <td class="amount">{{ $item->formatted_subtotal }}</td>
            </tr>
            @endforeach
            <tr class="inv-divider">
                <td>PPN 11%</td>
                <td class="amount">Rp {{ number_format($trx->tax, 0, ',', '.') }}</td>
            </tr>
            <tr class="inv-total">
                <td>Total</td>
                <td class="amount">{{ $trx->formatted_total }}</td>
            </tr>
            <tr>
                <td style="color:#6b7280;">Status</td>
                <td class="amount"><span class="badge badge-{{ $trx->payment_status === 'paid' ? 'green' : 'red' }}">{{ $trx->payment_status_label }}</span></td>
            </tr>
        </table>
    </div>
    @endif

    {{-- Tanda Tangan --}}
    <div class="section">
        <div class="section-title">Tanda Tangan Customer</div>
        @if($result->customer_signature)
        <div class="signature-box">
            <img src="{{ $result->customer_signature }}" class="signature-img" alt="Tanda Tangan">
        </div>
        <div class="signature-note">Ditandatangani pada {{ $result->signed_at ? $result->signed_at->isoFormat('D MMMM Y, HH:mm') : '-' }}</div>
        @else
        <div class="signature-box">
            <span class="signature-unsigned">Laporan ini belum ditandatangani oleh customer.</span>
        </div>
        @endif
    </div>

    <div class="footer">
        Dokumen ini diterbitkan secara otomatis oleh sistem Smart Otto &middot; {{ \Carbon\Carbon::now()->isoFormat('D MMMM Y, HH:mm') }}
    </div>

</div>
</body>
</html>
