<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Tariff;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = Transaction::with(['booking.user', 'booking.vehicle', 'booking.package', 'items'])
            ->orderByDesc('created_at');

        if ($request->status) {
            $query->where('payment_status', $request->status);
        }

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('transaction_code', 'like', '%' . $request->search . '%')
                  ->orWhereHas('booking', function ($bq) use ($request) {
                      $bq->where('booking_code', 'like', '%' . $request->search . '%')
                         ->orWhereHas('user', fn($u) => $u->where('name', 'like', '%' . $request->search . '%'));
                  });
            });
        }

        $transactions = $query->paginate(15)->withQueryString();

        // Statistics
        $stats = [
            'total_income'     => Transaction::where('payment_status', 'paid')->sum('total'),
            'pending_count'    => Transaction::where('payment_status', 'pending')->count(),
            'unpaid_count'     => Transaction::where('payment_status', 'unpaid')->count(),
            'paid_count'       => Transaction::where('payment_status', 'paid')->count(),
        ];

        return view('admin.transactions.index', compact('transactions', 'stats'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'package', 'inspector', 'inspectionResult']);
        $transaction = $booking->transaction()->with('items.tariff')->first();
        $tariffs     = Tariff::active()->orderBy('category')->orderBy('name')->get();
        $paymentConfig = config('payment');

        return view('transaction.show', compact('booking', 'transaction', 'tariffs', 'paymentConfig'));
    }

    public function store(Request $request, Booking $booking)
    {
        abort_if($booking->transaction, 422, 'Transaksi sudah ada.');

        $transaction = Transaction::create([
            'transaction_code' => Transaction::generateCode(),
            'booking_id'       => $booking->id,
            'subtotal'         => 0,
            'tax'              => 0,
            'discount'         => 0,
            'total'            => 0,
            'payment_status'   => 'unpaid',
        ]);

        // Tambah biaya paket inspeksi otomatis
        $pkg = $booking->package;
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

        return redirect()->route('transaction.show', $booking)
            ->with('success', 'Transaksi berhasil dibuat.');
    }

    public function addItem(Request $request, Booking $booking)
    {
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
                'payment_status'   => 'unpaid',
            ]);
        }

        $subtotal = $request->price * $request->quantity;

        if ($request->tariff_id) {
            $tariff = Tariff::findOrFail($request->tariff_id);
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'tariff_id'      => $tariff->id,
                'item_name'      => $tariff->name,
                'category'       => $tariff->category,
                'price'          => $tariff->price,
                'quantity'       => $request->quantity,
                'unit'           => $tariff->unit,
                'subtotal'       => $tariff->price * $request->quantity,
                'notes'          => $request->notes,
            ]);
        } else {
            TransactionItem::create([
                'transaction_id' => $transaction->id,
                'tariff_id'      => null,
                'item_name'      => $request->item_name,
                'category'       => $request->category,
                'price'          => $request->price,
                'quantity'       => $request->quantity,
                'unit'           => $request->unit,
                'subtotal'       => $subtotal,
                'notes'          => $request->notes,
            ]);
        }

        $transaction->recalculate();

        return back()->with('success', 'Item transaksi berhasil ditambahkan.');
    }

    public function removeItem(Booking $booking, TransactionItem $item)
    {
        abort_if($item->transaction->booking_id !== $booking->id, 403);
        $transaction = $item->transaction;
        $item->delete();
        $transaction->recalculate();
        return back()->with('success', 'Item berhasil dihapus.');
    }

    public function updateDiscount(Request $request, Booking $booking)
    {
        $request->validate([
            'discount' => 'required|numeric|min:0',
        ]);

        $transaction = $booking->transaction;
        abort_if(!$transaction, 404, 'Transaksi tidak ditemukan.');

        $transaction->discount = $request->discount;
        $transaction->recalculate();

        return back()->with('success', 'Diskon berhasil diperbarui.');
    }

    public function payment(Request $request, Booking $booking)
    {
        $request->validate([
            'payment_method'  => 'required|string|max:100',
            'payment_channel' => 'nullable|string|max:100',
            'payment_status'  => 'required|in:unpaid,pending,partial,paid,rejected',
            'admin_notes'     => 'nullable|string|max:500',
        ]);

        $transaction = $booking->transaction;
        abort_if(!$transaction, 422, 'Transaksi belum dibuat.');

        $transaction->update([
            'payment_status'  => $request->payment_status,
            'payment_method'  => $request->payment_method,
            'payment_channel' => $request->payment_channel ?? $request->payment_method,
            'admin_notes'     => $request->admin_notes,
            'paid_at'         => $request->payment_status === 'paid' ? ($transaction->paid_at ?? now()) : null,
        ]);

        return back()->with('success', 'Status dan metode pembayaran berhasil diperbarui.');
    }

    public function confirmPayment(Request $request, Booking $booking)
    {
        $transaction = $booking->transaction;
        abort_if(!$transaction, 404);

        $transaction->update([
            'payment_status' => 'paid',
            'paid_at'        => now(),
            'admin_notes'    => $request->admin_notes ?? 'Diverifikasi dan disetujui oleh admin.',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi LUNAS.');
    }

    public function rejectPayment(Request $request, Booking $booking)
    {
        $request->validate([
            'admin_notes' => 'required|string|max:500',
        ], [
            'admin_notes.required' => 'Masukkan alasan penolakan bukti pembayaran.',
        ]);

        $transaction = $booking->transaction;
        abort_if(!$transaction, 404);

        $transaction->update([
            'payment_status' => 'rejected',
            'admin_notes'    => $request->admin_notes,
        ]);

        return back()->with('success', 'Bukti pembayaran berhasil ditolak. Notifikasi akan ditampilkan kepada customer.');
    }

    public function invoice(Booking $booking)
    {
        $booking->load(['user', 'vehicle', 'package', 'transaction.items']);
        $transaction = $booking->transaction;
        abort_if(!$transaction, 404);

        return view('transaction.invoice', compact('booking', 'transaction'));
    }
}
