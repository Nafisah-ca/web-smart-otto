<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $bookings = Booking::with(['user','vehicle','package','inspector','transaction'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $mon)
            ->orderBy('booking_date')
            ->get();

        $revenue = Transaction::where('payment_status', 'paid')
            ->whereYear('paid_at', $year)
            ->whereMonth('paid_at', $mon)
            ->sum('total');

        $statusSummary = $bookings->groupBy('status')->map->count();

        return view('admin.reports.index', compact('bookings', 'revenue', 'statusSummary', 'month'));
    }

    public function export(Request $request)
    {
        $month = $request->get('month', now()->format('Y-m'));
        [$year, $mon] = explode('-', $month);

        $bookings = Booking::with(['user','vehicle','package','inspector','transaction'])
            ->whereYear('booking_date', $year)
            ->whereMonth('booking_date', $mon)
            ->orderBy('booking_date')
            ->get();

        $filename = "laporan-smartotto-{$month}.csv";
        $headers  = ['Content-Type' => 'text/csv', 'Content-Disposition' => "attachment; filename={$filename}"];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Kode Booking','Tanggal','Customer','Kendaraan','Paket','Inspektor','Status','Total Tagihan']);
            foreach ($bookings as $b) {
                fputcsv($file, [
                    $b->booking_code,
                    $b->booking_date->format('d/m/Y'),
                    $b->user->name,
                    $b->vehicle->brand . ' ' . $b->vehicle->model . ' - ' . $b->vehicle->plate_number,
                    $b->package->name,
                    $b->inspector?->name ?? '-',
                    $b->status_label,
                    $b->transaction ? $b->transaction->formatted_total : '-',
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
