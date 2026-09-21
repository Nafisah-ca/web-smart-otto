<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('user_id', Auth::id())
            ->with(['vehicle', 'package'])
            ->orderByDesc('created_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(10);
        return view('customer.history.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['vehicle', 'package', 'inspector', 'inspectionResult', 'transaction.items']);
        return view('customer.history.show', compact('booking'));
    }

    public function report(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $result = $booking->inspectionResult;
        abort_if(!$result || !$result->is_verified, 403, 'Laporan belum tersedia atau belum diverifikasi.');

        $booking->load(['vehicle', 'package', 'inspector', 'transaction.items']);
        return view('customer.history.report', compact('booking', 'result'));
    }

    public function sign(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);

        $request->validate([
            'signature' => 'required|string',
        ]);

        $result = $booking->inspectionResult;
        abort_if(!$result || !$result->is_verified, 403);

        $result->update([
            'customer_signature' => $request->signature,
            'signed_at'          => now(),
        ]);

        return redirect()->route('customer.history.report', $booking)
            ->with('success', 'Tanda tangan berhasil disimpan!');
    }
}
