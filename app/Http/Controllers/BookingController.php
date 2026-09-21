<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\InspectionPackage;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    // Slot jam tersedia
    private array $allSlots = [
        '08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00',
    ];

    public function create(Request $request)
    {
        $packages  = InspectionPackage::active()->get();
        $vehicles  = Auth::user()->vehicles;
        $selected  = $request->query('package');

        return view('booking.create', compact('packages', 'vehicles', 'selected'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id'   => 'required|exists:inspection_packages,id',
            'vehicle_type' => 'required|in:existing,new',
            'vehicle_id'   => 'required_if:vehicle_type,existing|nullable|exists:vehicles,id',
            'brand'        => 'required_if:vehicle_type,new|nullable|string|max:100',
            'model'        => 'required_if:vehicle_type,new|nullable|string|max:100',
            'plate_number' => 'required_if:vehicle_type,new|nullable|string|max:20',
            'year'         => 'required_if:vehicle_type,new|nullable|integer|min:1990|max:' . date('Y'),
            'type'         => 'required_if:vehicle_type,new|nullable|in:motor,mobil,truk,bus',
            'color'        => 'nullable|string|max:50',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'notes'        => 'nullable|string|max:500',
        ], [
            'package_id.required'   => 'Pilih paket inspeksi.',
            'booking_date.required' => 'Pilih tanggal booking.',
            'booking_date.after_or_equal' => 'Tanggal tidak boleh lampau.',
            'booking_time.required' => 'Pilih jam booking.',
        ]);

        // Validasi slot tersedia
        $slotTaken = Booking::where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time . ':00')
            ->whereNotIn('status', ['cancelled'])
            ->count();

        $maxPerSlot = 3; // maks 3 booking per slot
        if ($slotTaken >= $maxPerSlot) {
            return back()->withErrors(['booking_time' => 'Slot jam ini sudah penuh. Pilih jam lain.'])->withInput();
        }

        // Buat atau gunakan kendaraan
        if ($request->vehicle_type === 'new') {
            $vehicle = Vehicle::create([
                'user_id'      => Auth::id(),
                'brand'        => $request->brand,
                'model'        => $request->model,
                'plate_number' => strtoupper($request->plate_number),
                'year'         => $request->year,
                'type'         => $request->type,
                'color'        => $request->color,
            ]);
            $vehicleId = $vehicle->id;
        } else {
            // Pastikan kendaraan milik user ini
            $vehicle = Vehicle::where('id', $request->vehicle_id)
                ->where('user_id', Auth::id())->firstOrFail();
            $vehicleId = $vehicle->id;
        }

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id'      => Auth::id(),
            'vehicle_id'   => $vehicleId,
            'package_id'   => $request->package_id,
            'inspector_id' => null,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time . ':00',
            'status'       => 'pending',
            'notes'        => $request->notes,
        ]);

        return redirect()->route('booking.success', $booking)
            ->with('success', 'Booking berhasil dibuat!');
    }

    public function success(Booking $booking)
    {
        // Pastikan booking milik user ini
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load('vehicle', 'package');
        return view('booking.success', compact('booking'));
    }

    public function getSlots(Request $request)
    {
        $date = $request->query('date');
        if (!$date) return response()->json([]);

        $booked = Booking::where('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('booking_time, COUNT(*) as total')
            ->groupBy('booking_time')
            ->pluck('total', 'booking_time');

        $maxPerSlot = 3;
        $slots = [];
        foreach ($this->allSlots as $slot) {
            $timeKey = $slot . ':00';
            $slots[] = [
                'time'      => $slot,
                'available' => ($booked[$timeKey] ?? 0) < $maxPerSlot,
                'remaining' => $maxPerSlot - ($booked[$timeKey] ?? 0),
            ];
        }

        return response()->json($slots);
    }
}
