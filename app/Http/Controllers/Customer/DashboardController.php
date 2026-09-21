<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $activeBookings = Booking::where('user_id', $user->id)
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with(['vehicle', 'package', 'inspector'])
            ->orderByDesc('created_at')
            ->get();

        $recentCompleted = Booking::where('user_id', $user->id)
            ->where('status', 'completed')
            ->with(['vehicle', 'package'])
            ->orderByDesc('updated_at')
            ->limit(3)
            ->get();

        $stats = [
            'total'     => Booking::where('user_id', $user->id)->count(),
            'active'    => $activeBookings->count(),
            'completed' => Booking::where('user_id', $user->id)->where('status', 'completed')->count(),
            'cancelled' => Booking::where('user_id', $user->id)->where('status', 'cancelled')->count(),
        ];

        return view('customer.dashboard', compact('activeBookings', 'recentCompleted', 'stats'));
    }
}
