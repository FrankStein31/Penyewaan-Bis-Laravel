<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Rental;

class MidtransController extends Controller
{
    public function index()
    {
        // Ambil semua pembayaran Midtrans
        $payments = Payment::with(['rental.user'])
            ->where('payment_method', 'midtrans')
            ->latest()
            ->get()
            ->map(function ($payment) {
                return [
                    'id' => $payment->id,
                    'payment_code' => $payment->payment_code,
                    'amount' => $payment->amount,
                    'status' => $payment->status,
                    'created_at' => $payment->created_at,
                    'customer_name' => $payment->rental->user->firstname . ' ' . $payment->rental->user->lastname,
                    'rental_code' => $payment->rental->rental_code,
                ];
            });

        // Statistik pembayaran Midtrans
        $totalTransactions = $payments->count();
        $successTransactions = $payments->where('status', 'success')->count();
        $pendingTransactions = $payments->where('status', 'pending')->count();
        $failedTransactions = $payments->where('status', 'failed')->count();
        $totalAmount = $payments->where('status', 'success')->sum('amount');

        return view('admin.midtrans.index', compact(
            'payments',
            'totalTransactions',
            'successTransactions', 
            'pendingTransactions',
            'failedTransactions',
            'totalAmount'
        ));
    }

    public function dashboard()
    {
        return view('admin.midtrans.dashboard');
    }
}