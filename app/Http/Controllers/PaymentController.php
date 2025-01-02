<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    public function pay(Request $request, Rental $rental)
    {
        try {
            $request->validate([
                'amount' => 'required|numeric|min:1',
                'payment_method' => 'required|string',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                'notes' => 'nullable|string'
            ]);

            // Validasi jumlah pembayaran
            $totalPaid = $rental->payments()->where('status', 'success')->sum('amount');
            $remainingAmount = $rental->total_price - $totalPaid;
            
            if ($request->amount > $remainingAmount) {
                return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan');
            }

            DB::beginTransaction();

            // Generate payment code
            $prefix = 'PAY';
            $date = now()->format('Ymd');
            $lastPayment = Payment::whereDate('created_at', today())
                ->latest()
                ->first();

            if ($lastPayment) {
                $lastNumber = intval(substr($lastPayment->payment_code, -4));
                $newNumber = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '0001';
            }

            $paymentCode = $prefix . $date . $newNumber;

            // Upload bukti pembayaran
            $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

            // Buat payment record
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'payment_code' => $paymentCode,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            // Update payment status rental
            $newTotalPaid = $totalPaid + $request->amount;
            if ($newTotalPaid >= $rental->total_price) {
                $rental->update(['payment_status' => 'paid']);
            } else {
                $rental->update(['payment_status' => 'partial']);
            }

            DB::commit();
            return back()->with('success', 'Pembayaran berhasil disubmit. Mohon tunggu verifikasi admin.');

        } catch (\Exception $e) {
            DB::rollBack();
            if (isset($paymentProofPath)) {
                Storage::disk('public')->delete($paymentProofPath);
            }
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function index()
    {
        try {
            // Ambil rental yang perlu pembayaran (confirmed & aktif)
            $activeRentals = Rental::where('user_id', auth()->id())
                ->where('status', 'aktif')
                ->where('rental_status', 'confirmed')
                ->with(['bus', 'payments' => function($query) {
                    $query->where('status', 'success')
                          ->orderBy('created_at', 'desc');
                }])
                ->get()
                ->map(function($rental) {
                    // Hitung total yang sudah dibayar
                    $totalPaid = $rental->payments->sum('amount');
                    $remainingAmount = $rental->total_price - $totalPaid;
                    
                    return [
                        'rental' => $rental,
                        'total_paid' => $totalPaid,
                        'remaining_amount' => $remainingAmount,
                        'payment_history' => $rental->payments
                    ];
                });

            // Ambil riwayat pembayaran untuk semua rental
            $paymentHistory = Rental::where('user_id', auth()->id())
                ->whereHas('payments', function($query) {
                    $query->where('status', 'success');
                })
                ->with(['bus', 'payments' => function($query) {
                    $query->where('status', 'success')
                          ->orderBy('created_at', 'desc');
                }])
                ->get();

            return view('customer.payments.index', compact('activeRentals', 'paymentHistory'));
        } catch (\Exception $e) {
            \Log::error('Payment Index Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran');
        }
    }

    public function showPaymentForm(Rental $rental)
    {
        try {
            // Pastikan rental milik user yang sedang login
            if ($rental->user_id !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses ke rental ini');
            }

            // Hitung total yang sudah dibayar
            $totalPaid = $rental->payments()
                               ->where('status', 'success')
                               ->sum('amount');
            
            // Hitung sisa yang harus dibayar
            $remainingAmount = $rental->total_price - $totalPaid;

            // Jika sudah lunas
            if ($remainingAmount <= 0) {
                return back()->with('error', 'Pembayaran untuk rental ini sudah lunas');
            }

            return view('customer.payments.form', compact('rental', 'totalPaid', 'remainingAmount'));
        } catch (\Exception $e) {
            \Log::error('Show Payment Form Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat form pembayaran');
        }
    }

    public function adminIndex()
    {
        try {
            // Ambil semua pembayaran yang sudah dilakukan
            $payments = Payment::with(['rental.user', 'rental.bus'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($payment) {
                    return [
                        'id' => $payment->id,
                        'rental_code' => $payment->rental->rental_code,
                        'customer_name' => $payment->rental->user->firstname . ' ' . $payment->rental->user->lastname,
                        'bus_plate' => $payment->rental->bus->plate_number,
                        'amount' => $payment->amount,
                        'payment_method' => $payment->payment_method,
                        'proof' => $payment->payment_proof,
                        'status' => $payment->status,
                        'created_at' => $payment->created_at,
                        'rental_id' => $payment->rental_id
                    ];
                });

            // Ambil rental yang belum dibayar atau belum lunas
            $unpaidRentals = Rental::with(['user', 'bus'])
                ->where('status', 'aktif')
                ->where('rental_status', 'confirmed')
                ->where(function($query) {
                    $query->where('payment_status', 'unpaid')
                          ->orWhere('payment_status', 'partial');
                })
                ->get()
                ->map(function($rental) {
                    $totalPaid = $rental->payments()
                        ->where('status', 'success')
                        ->sum('amount');
                        
                    $remainingAmount = $rental->total_price - $totalPaid;
                    
                    return [
                        'rental_code' => $rental->rental_code,
                        'customer_name' => $rental->user->firstname . ' ' . $rental->user->lastname,
                        'bus_plate' => $rental->bus->plate_number,
                        'total_price' => $rental->total_price,
                        'total_paid' => $totalPaid,
                        'remaining_amount' => $remainingAmount,
                        'rental_id' => $rental->id,
                        'created_at' => $rental->created_at,
                        'payment_status' => $rental->payment_status
                    ];
                });

            return view('admin.payments.index', compact('payments', 'unpaidRentals'));
        } catch (\Exception $e) {
            \Log::error('Admin Payment Index Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat data pembayaran');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'rental_id' => 'required|exists:rentals,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:transfer,cash',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $rental = Rental::findOrFail($request->rental_id);
            
            // Cek apakah rental milik user yang login
            if ($rental->user_id !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses ke penyewaan ini');
            }

            // Buat payment baru
            $payment = new Payment();
            $payment->rental_id = $request->rental_id;
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;
            $payment->status = 'pending';

            // Upload bukti pembayaran jika metode transfer
            if ($request->payment_method === 'transfer' && $request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/payment_proofs', $filename);
                $payment->payment_proof = $filename;
            }

            $payment->save();

            // Redirect ke halaman pembayaran dengan pesan sukses
            return redirect()->route('customer.payments')
                            ->with('success', 'Pembayaran berhasil disubmit dan menunggu konfirmasi admin');

        } catch (\Exception $e) {
            \Log::error('Payment Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memproses pembayaran');
        }
    }
}