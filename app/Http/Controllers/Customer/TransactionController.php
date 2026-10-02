<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\TransactionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Transaction::whereHas('booking', function ($q) use ($user) {
            $q->where('user_id', $user->id);
        })->with(['booking.vehicle', 'booking.package', 'items'])->orderByDesc('created_at');

        if ($request->status) {
            $query->where('payment_status', $request->status);
        }

        $transactions = $query->paginate(10)->withQueryString();

        return view('customer.transactions.index', compact('transactions'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $booking->load(['vehicle', 'package', 'inspector', 'inspectionResult']);

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
            $transaction->refresh();
        }

        $transaction->load('items.tariff');
        $paymentConfig = config('payment');

        return view('customer.transactions.show', compact('booking', 'transaction', 'paymentConfig'));
    }

    public function pay(Request $request, Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id(), 403, 'Akses ditolak.');

        $transaction = $booking->transaction;
        abort_if(!$transaction, 404, 'Transaksi tidak ditemukan.');

        $request->validate([
            'payment_method'    => 'required|string|max:100',
            'payment_channel'   => 'nullable|string|max:100',
            'payment_reference' => 'nullable|string|max:255',
            'payment_proof'     => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'notes'             => 'nullable|string|max:500',
        ], [
            'payment_method.required' => 'Pilih metode pembayaran terlebih dahulu.',
            'payment_proof.image'     => 'Bukti pembayaran harus berupa gambar.',
            'payment_proof.max'       => 'Ukuran bukti transfer maksimal 5MB.',
        ]);

        $proofPath = $transaction->payment_proof;

        if ($request->hasFile('payment_proof')) {
            $file = $request->file('payment_proof');
            $filename = 'proof_' . $transaction->transaction_code . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads/payments'), $filename);
            $proofPath = 'uploads/payments/' . $filename;
        }

        $transaction->update([
            'payment_method'    => $request->payment_method,
            'payment_channel'   => $request->payment_channel ?? $request->payment_method,
            'payment_reference' => $request->payment_reference,
            'payment_proof'     => $proofPath,
            'notes'             => $request->notes ?? $transaction->notes,
            'payment_status'    => 'pending', // Menunggu konfirmasi admin
        ]);

        return back()->with('success', 'Konfirmasi pembayaran berhasil dikirim! Admin kami akan segera memverifikasi transaksi Anda.');
    }

    public function invoice(Booking $booking)
    {
        abort_if($booking->user_id !== Auth::id() && !Auth::user()->isAdmin(), 403);
        $booking->load(['user', 'vehicle', 'package', 'transaction.items']);
        $transaction = $booking->transaction;
        abort_if(!$transaction, 404, 'Transaksi tidak ditemukan.');

        return view('transaction.invoice', compact('booking', 'transaction'));
    }
}
