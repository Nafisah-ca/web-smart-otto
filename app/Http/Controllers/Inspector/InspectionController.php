<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InspectionChecklistItem;
use App\Models\InspectionResult;
use App\Models\Tariff;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('inspector_id', Auth::id())
            ->with(['user','vehicle','package'])
            ->orderBy('booking_date')
            ->orderBy('created_at');      // yang udah booking dari tadi di atas, baru booking di bawah

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15)->withQueryString();
        $groupedBookings = $bookings->getCollection()->groupBy(fn($b) => $b->booking_date->format('Y-m-d'));  // pisah per tanggal

        return view('inspector.tasks.index', compact('bookings', 'groupedBookings'));
    }

    public function show(Booking $booking)
    {
        $this->authorizeInspector($booking);
        $booking->load(['user','vehicle','package','inspectionResult','transaction.items.tariff']);
        $tariffs = Tariff::active()->orderBy('category')->orderBy('name')->get();
        return view('inspector.tasks.show', compact('booking', 'tariffs'));
    }

    public function form(Booking $booking)
    {
        $this->authorizeInspector($booking);
        $booking->load(['vehicle', 'inspectionResult', 'transaction.items.tariff']);

        // Tandai sebagai on_progress jika masih confirmed/waiting
        if (in_array($booking->status, ['confirmed', 'waiting'])) {
            $booking->update(['status' => 'on_progress']);
        }

        // Load 81 poin aktif langsung — tidak lagi via relasi paket
        $checklistItems = InspectionChecklistItem::active()
            ->orderBy('sort_order')
            ->get();

        $existingResult = $booking->inspectionResult;
        $tariffs = Tariff::active()->orderBy('category')->orderBy('name')->get();

        return view('inspector.tasks.form', compact('booking', 'checklistItems', 'existingResult', 'tariffs'));
    }

    public function store(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        $validStatuses = implode(',', array_keys(\App\Models\InspectionChecklistItem::$statusOptions));

        $request->validate([
            'checklist'         => 'required|array',
            'checklist.*.status'=> 'required|in:' . $validStatuses,
            'condition_summary' => 'required|in:baik,cukup,perlu_perhatian,kritis',
            'recommendation'    => 'nullable|string',
            'inspector_notes'   => 'nullable|string',
        ]);

        InspectionResult::updateOrCreate(
            ['booking_id' => $booking->id],
            [
                'checklist_json'    => $request->checklist,
                'condition_summary' => $request->condition_summary,
                'recommendation'    => $request->recommendation,
                'inspector_notes'   => $request->inspector_notes,
                'completed_at'      => now(),
            ]
        );

        return redirect()->route('inspector.tasks.show', $booking)
            ->with('success', 'Hasil inspeksi berhasil disimpan.');
    }

    public function update(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        $validStatuses = implode(',', array_keys(\App\Models\InspectionChecklistItem::$statusOptions));

        $request->validate([
            'checklist'         => 'required|array',
            'checklist.*.status'=> 'required|in:' . $validStatuses,
            'condition_summary' => 'required|in:baik,cukup,perlu_perhatian,kritis',
            'recommendation'    => 'nullable|string',
            'inspector_notes'   => 'nullable|string',
        ]);

        $booking->inspectionResult->update([
            'checklist_json'    => $request->checklist,
            'condition_summary' => $request->condition_summary,
            'recommendation'    => $request->recommendation,
            'inspector_notes'   => $request->inspector_notes,
        ]);

        return redirect()->route('inspector.tasks.show', $booking)
            ->with('success', 'Hasil inspeksi berhasil diperbarui.');
    }

    public function verify(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        $result = $booking->inspectionResult;
        abort_if(!$result, 422, 'Hasil inspeksi belum diisi.');

        $result->update([
            'is_verified' => true,
            'verified_at' => now(),
        ]);

        $booking->update(['status' => 'completed']);

        return redirect()->route('inspector.tasks.show', $booking)
            ->with('success', 'Laporan inspeksi & item tambahan berhasil diverifikasi dan dikirim ke admin untuk penetapan harga final.');
    }

    public function addItem(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        abort_if($booking->inspectionResult?->is_verified, 422, 'Laporan inspeksi sudah diverifikasi dan dikirim ke admin. Item tidak dapat diubah lagi.');

        $request->validate([
            'tariff_id' => 'nullable|exists:tariff,id',
            'item_name' => 'required|string|max:255',
            'category'  => 'required|string|max:100',
            'price'     => 'required|numeric|min:0',
            'quantity'  => 'required|integer|min:1',
            'unit'      => 'required|string|max:20',
            'notes'     => 'nullable|string|max:255',
        ]);

        $transaction = $booking->transaction;
        if (!$transaction) {
            $transaction = Transaction::create([
                'transaction_code' => Transaction::generateCode(),
                'booking_id'       => $booking->id,
                'subtotal'         => 0,
                'tax'              => 0,
                'discount'         => 0,
                'total'            => 0,
                'payment_status'   => 'price_pending',
            ]);
        }

        TransactionItem::create([
            'transaction_id' => $transaction->id,
            'tariff_id'      => $request->tariff_id ?: null,
            'item_name'      => $request->item_name,
            'category'       => $request->category,
            'price'          => $request->price,
            'quantity'       => $request->quantity,
            'unit'           => $request->unit,
            'subtotal'       => $request->price * $request->quantity,
            'notes'          => $request->notes,
        ]);

        $transaction->recalculate();

        return back()->with('success', "Item '{$request->item_name}' berhasil ditambahkan ke laporan.");
    }

    public function removeItem(Booking $booking, TransactionItem $item)
    {
        $this->authorizeInspector($booking);
        abort_if($item->transaction->booking_id !== $booking->id, 403);
        abort_if($item->category === 'Inspeksi', 422, 'Biaya inspeksi utama tidak dapat dihapus.');
        abort_if($booking->inspectionResult?->is_verified, 422, 'Laporan inspeksi sudah diverifikasi. Item tidak dapat dihapus lagi.');

        $transaction = $item->transaction;
        $name = $item->item_name;
        $item->delete();
        $transaction->recalculate();

        return back()->with('success', "Item '{$name}' berhasil dihapus dari laporan.");
    }

    public function uploadPhoto(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        $request->validate([
            'photo' => 'required|image|max:5120', // max 5MB
        ]);

        $path   = $request->file('photo')->store('inspections/' . $booking->id, 'public');
        $result = $booking->inspectionResult;

        if (!$result) {
            $result = InspectionResult::create(['booking_id' => $booking->id]);
        }

        $photos   = $result->photos ?? [];
        $photos[] = $path;
        $result->update(['photos' => $photos]);

        return response()->json(['success' => true, 'path' => $path, 'url' => asset('storage/' . $path)]);
    }

    private function authorizeInspector(Booking $booking): void
    {
        abort_if($booking->inspector_id !== Auth::id(), 403, 'Anda tidak memiliki akses ke booking ini.');
    }
}
