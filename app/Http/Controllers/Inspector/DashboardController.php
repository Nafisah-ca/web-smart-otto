<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $todayTasks = Booking::where('inspector_id', $user->id)
            ->whereDate('booking_date', today())
            ->with(['user','vehicle','package'])
            ->orderBy('booking_time')
            ->get();

        $pendingTasks = Booking::where('inspector_id', $user->id)
            ->whereIn('status', ['confirmed','waiting','on_progress'])
            ->with(['user','vehicle','package'])
            ->orderBy('booking_date')
            ->orderBy('booking_time')
            ->get();

        $stats = [
            'total'       => Booking::where('inspector_id', $user->id)->count(),
            'on_progress' => Booking::where('inspector_id', $user->id)->where('status', 'on_progress')->count(),
            'completed'   => Booking::where('inspector_id', $user->id)->where('status', 'completed')->count(),
            'today'       => $todayTasks->count(),
        ];

        return view('inspector.dashboard', compact('todayTasks', 'pendingTasks', 'stats'));
    }
}
