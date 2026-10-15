<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Models\Vehicle;
use App\Models\VehicleCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookingController extends Controller
{
    private array $allSlots = [
        '08:00', '09:00', '10:00', '11:00', '13:00', '14:00', '15:00', '16:00',
    ];

    public function create(Request $request)
    {
        $vehicles = Auth::check() ? Auth::user()->vehicles : collect();
        return view('booking.create', compact('vehicles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'vehicle_type' => 'required|in:existing,new',
            'vehicle_id'   => 'required_if:vehicle_type,existing|nullable|exists:vehicle,id',
            'brand'        => 'required_if:vehicle_type,new|nullable|string|max:100',
            'model'        => 'required_if:vehicle_type,new|nullable|string|max:100',
            'plate_number' => 'required_if:vehicle_type,new|nullable|string|max:20',
            'year'         => 'required_if:vehicle_type,new|nullable|integer|min:1990|max:' . date('Y'),
            'type'         => 'required_if:vehicle_type,new|nullable|in:mobil,truk',
            'color'        => 'nullable|string|max:50',
            'category_id'  => 'nullable|exists:vehicle_category,id',
            'booking_date' => 'required|date|after_or_equal:today',
            'booking_time' => 'required|string',
            'notes'        => 'nullable|string|max:500',
        ], [
            'booking_date.required'       => 'Pilih tanggal booking.',
            'booking_date.after_or_equal' => 'Tanggal tidak boleh lampau.',
            'booking_time.required'       => 'Pilih jam booking.',
        ]);

        // Validasi slot tersedia
        $slotTaken = Booking::where('booking_date', $request->booking_date)
            ->where('booking_time', $request->booking_time . ':00')
            ->whereNotIn('status', ['cancelled'])
            ->count();

        if ($slotTaken >= 3) {
            return back()->withErrors(['booking_time' => 'Slot jam ini sudah penuh. Pilih jam lain.'])->withInput();
        }

        // Buat atau gunakan kendaraan
        if ($request->vehicle_type === 'new') {
            // Jika plat sudah terdaftar milik user ini, pakai yang lama
            $vehicle = Vehicle::firstOrCreate(
                [
                    'plate_number' => strtoupper($request->plate_number),
                    'user_id'      => Auth::id(),
                ],
                [
                    'brand' => $request->brand,
                    'model' => $request->model,
                    'year'  => $request->year,
                    'type'  => $request->type,
                    'color' => $request->color,
                ]
            );
        } else {
            $vehicle = Vehicle::where('id', $request->vehicle_id)
                ->where('user_id', Auth::id())->firstOrFail();
        }

        $booking = Booking::create([
            'booking_code' => Booking::generateCode(),
            'user_id'      => Auth::id(),
            'vehicle_id'   => $vehicle->id,
            'package_id'   => null,
            'category_id'  => $request->category_id ?: null,
            'final_price'  => null,
            'inspector_id' => null,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time . ':00',
            'status'       => 'pending',
            'notes'        => $request->notes,
        ]);

        // Harga default = price_min kategori (admin bisa ubah nanti)
        $category   = $booking->vehicleCategory;
        $price      = $category ? (float) $category->price_min : 0;
        $itemName   = 'Biaya Inspeksi' . ($category ? ' - ' . $category->name : '');

        // Simpan final_price ke booking
        if ($price > 0) {
            $booking->update(['final_price' => $price]);
        }

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'booking_id'       => $booking->id,
            'subtotal'         => 0,
            'tax'              => 0,
            'discount'         => 0,
            'total'            => 0,
            'payment_status'   => 'price_pending', // menunggu konfirmasi harga admin
        ]);

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'tariff_id'      => null,
            'item_name'      => $itemName,
            'category'       => 'Inspeksi',
            'price'          => $price,
            'quantity'       => 1,
            'unit'           => 'unit',
            'subtotal'       => $price,
        ]);

        $transaction->recalculate();

        return redirect()->route('booking.success', $booking)
            ->with('success', 'Booking berhasil dibuat!');
    }

    public function success(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403);
        $booking->load(['vehicle', 'vehicleCategory', 'transaction.items']);
        return view('booking.success', compact('booking'));
    }

    /**
     * AJAX endpoint: deteksi kategori berdasarkan model kendaraan.
     * GET /booking/detect-category?model=Avanza
     */
    public function detectCategory(Request $request)
    {
        $model = $request->query('model', '');
        [$category, $ambiguous] = VehicleCategory::detectFromModel($model);

        if ($ambiguous) {
            return response()->json(['ambiguous' => true]);
        }

        if ($category) {
            return response()->json([
                'ambiguous' => false,
                'category'  => [
                    'id'             => $category->id,
                    'name'           => $category->name,
                    'formatted_range'=> $category->formatted_range,
                    'price_min'      => $category->price_min,
                    'price_max'      => $category->price_max,
                ],
            ]);
        }

        return response()->json(['ambiguous' => false, 'category' => null]);
    }

    public function getSlots(Request $request)
    {
        $date = $request->query('date');
        if (! $date) return response()->json([]);

        // Ambil slot dari CMS, fallback ke default
        $cmsSlots = \App\Models\CmsContent::get('booking_slots', '');
        if ($cmsSlots) {
            $allSlots = array_values(array_filter(array_map('trim', explode(',', $cmsSlots))));
        } else {
            $allSlots = $this->allSlots;
        }

        $booked = Booking::where('booking_date', $date)
            ->whereNotIn('status', ['cancelled'])
            ->selectRaw('booking_time, COUNT(*) as total')
            ->groupBy('booking_time')
            ->pluck('total', 'booking_time');

        $maxPerSlot = 3;
        $isToday    = $date === now()->toDateString();
        $nowHour    = now()->format('H:i'); // misal "14:30"

        $slots = [];
        foreach ($allSlots as $slot) {
            // Filter: kalau hari ini dan jam sudah lewat, skip
            if ($isToday && $slot <= $nowHour) {
                continue;
            }

            $timeKey  = $slot . ':00';
            $slots[]  = [
                'time'      => $slot,
                'available' => ($booked[$timeKey] ?? 0) < $maxPerSlot,
                'remaining' => $maxPerSlot - ($booked[$timeKey] ?? 0),
            ];
        }

        return response()->json($slots);
    }
}
