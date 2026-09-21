<?php

namespace App\Http\Controllers\Inspector;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\InspectionResult;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class InspectionController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::where('inspector_id', Auth::id())
            ->with(['user','vehicle','package'])
            ->orderByDesc('booking_date');

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $bookings = $query->paginate(15)->withQueryString();
        return view('inspector.tasks.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $this->authorizeInspector($booking);
        $booking->load(['user','vehicle','package','inspectionResult','transaction.items.tariff']);
        return view('inspector.tasks.show', compact('booking'));
    }

    public function form(Booking $booking)
    {
        $this->authorizeInspector($booking);
        $booking->load(['vehicle','package.checklistItems','inspectionResult']);

        // Tandai sebagai on_progress jika masih confirmed/waiting
        if (in_array($booking->status, ['confirmed','waiting'])) {
            $booking->update(['status' => 'on_progress']);
        }

        $checklistItems = $booking->package->checklistItems()->orderBy('category')->orderBy('sort_order')->get();
        $existingResult = $booking->inspectionResult;

        return view('inspector.tasks.form', compact('booking', 'checklistItems', 'existingResult'));
    }

    public function store(Request $request, Booking $booking)
    {
        $this->authorizeInspector($booking);

        $request->validate([
            'checklist'         => 'required|array',
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

        $request->validate([
            'checklist'         => 'required|array',
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
            ->with('success', 'Laporan berhasil diverifikasi. Booking selesai!');
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
