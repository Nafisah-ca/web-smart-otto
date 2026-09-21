<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Statistik booking per status
        $bookingStats = Booking::selectRaw('status, COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        // Statistik booking per hari (7 hari terakhir)
        $bookingChart = Booking::selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->pluck('total', 'date');

        // Revenue bulan ini
        $revenueMonth = Transaction::where('payment_status', 'paid')
            ->whereMonth('paid_at', now()->month)
            ->sum('total');

        // Booking hari ini
        $todayBookings = Booking::whereDate('booking_date', today())
            ->with(['user', 'vehicle', 'package', 'inspector'])
            ->orderBy('booking_time')
            ->get();

        // Pending bookings
        $pendingBookings = Booking::where('status', 'pending')
            ->with(['user', 'vehicle', 'package'])
            ->orderBy('booking_date')
            ->limit(5)
            ->get();

        $totalCustomers  = User::customers()->count();
        $totalInspectors = User::inspectors()->count();

        return view('admin.dashboard', compact(
            'bookingStats', 'bookingChart', 'revenueMonth',
            'todayBookings', 'pendingBookings',
            'totalCustomers', 'totalInspectors'
        ));
    }
}
