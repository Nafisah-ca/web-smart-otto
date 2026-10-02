<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeBookings = Booking::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['vehicle', 'package', 'inspector', 'transaction'])
            ->orderByDesc('created_at')
            ->get();

        $recentCompleted = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['vehicle', 'package', 'transaction'])
            ->orderByDesc('updated_at')
            ->limit(3)
            ->get();

        $unpaidTransactions = Transaction::whereHas('booking', fn($q) => $q->where('user_id', $user->id))
            ->whereIn('payment_status', ['unpaid', 'pending', 'rejected'])
            ->count();

        $stats = [
            'total'     => Booking::where('user_id', $user->id)->count(),
            'active'    => $activeBookings->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'unpaid'    => $unpaidTransactions,
        ];

        return view('customer.dashboard', compact('activeBookings', 'recentCompleted', 'stats'));
    }
}
