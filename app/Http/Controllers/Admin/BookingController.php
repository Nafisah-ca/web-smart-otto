<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InspectionPackage;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['user', 'vehicle', 'package', 'inspector', 'transaction'])
            ->orderByDesc('created_at');

        if ($request->status) {
            $query->where('status', $request->status);
        }
        if ($request->date) {
            $query->whereDate('booking_date', $request->date);
        }
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('booking_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
            });
        }

        $bookings   = $query->paginate(15)->withQueryString();
        $inspectors = User::inspectors()->orderBy('name')->get();

        return view('admin.bookings.index', compact('bookings', 'inspectors'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'package', 'inspector', 'inspectionResult', 'transaction.items.tariff']);
        $inspectors = User::inspectors()->orderBy('name')->get();
        return view('admin.bookings.show', compact('booking', 'inspectors'));
    }

    public function confirm(Booking $booking)
    {
        abort_if($booking->status !== 'pending', 422, 'Booking tidak dalam status pending.');
        $booking->update([
            'status'       => 'confirmed',
            'confirmed_at' => now(),
        ]);
        return back()->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function assign(Request $request, Booking $booking)
    {
        $request->validate(['inspector_id' => 'required|exists:user,id']);

        $inspector = User::findOrFail($request->inspector_id);
        abort_if($inspector->role !== 'inspector', 422, 'User bukan inspektor.');

        $booking->update(['inspector_id' => $request->inspector_id]);
        return back()->with('success', 'Inspektor berhasil di-assign.');
    }

    public function updateStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:confirmed,waiting,on_progress,completed,cancelled',
        ]);

        $booking->update(['status' => $request->status]);
        return back()->with('success', 'Status booking berhasil diperbarui.');
    }

    public function cancel(Request $request, Booking $booking)
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $booking->update([
            'status'               => 'cancelled',
            'cancellation_reason'  => $request->reason,
        ]);
        return back()->with('success', 'Booking berhasil dibatalkan.');
    }

    public function create()
    {
        $packages   = InspectionPackage::active()->get();
        $customers  = User::customers()->orderBy('name')->get();
        $inspectors = User::inspectors()->orderBy('name')->get();
        return view('admin.bookings.create', compact('packages', 'customers', 'inspectors'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|exists:user,id',
            'package_id'   => 'required|exists:inspection_package,id',
            'booking_date' => 'required|date',
            'booking_time' => 'required|string',
            'inspector_id' => 'nullable|exists:user,id',
            'notes'        => 'nullable|string|max:500',
        ]);

        // Pilih atau buat kendaraan
        $vehicle = null;
        if ($request->vehicle_id) {
            $vehicle = Vehicle::findOrFail($request->vehicle_id);
        } else {
            $request->validate([
                'brand'        => 'required|string',
                'model'        => 'required|string',
                'plate_number' => 'required|string',
                'year'         => 'required|integer',
                'type'         => 'required|in:motor,mobil,truk,bus',
            ]);
            $vehicle = Vehicle::create([
                'user_id'      => $request->user_id,
                'brand'        => $request->brand,
                'model'        => $request->model,
                'plate_number' => strtoupper($request->plate_number),
                'year'         => $request->year,
                'type'         => $request->type,
            ]);
        }

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id'      => $request->user_id,
            'vehicle_id'   => $vehicle->id,
            'package_id'   => $request->package_id,
            'inspector_id' => $request->inspector_id,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time . ':00',
            'status'       => 'confirmed',
            'notes'        => $request->notes,
            'confirmed_at' => now(),
        ]);

        // Otomatis buat transaksi jika belum ada
        $pkg = $booking->package;
        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'booking_id'       => $booking->id,
            'subtotal'         => 0,
            'tax'              => 0,
            'discount'         => 0,
            'total'            => 0,
            'payment_status'   => 'unpaid',
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'tariff_id'      => null,
            'item_name'      => 'Biaya Inspeksi - ' . $pkg->name,
            'category'       => 'Inspeksi',
            'price'          => $pkg->price,
            'quantity'       => 1,
            'unit'           => 'paket',
            'subtotal'       => $pkg->price,
        ]);

        $transaction->recalculate();

        return redirect()->route('admin.bookings.show', $booking)
            ->with('success', 'Booking berhasil dibuat oleh admin.');
    }
}
