<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Mail\RentalStatusMail;
use Illuminate\Support\Facades\Mail;
use App\Models\RentalExtension;
use App\Models\User;

class PaymentController extends Controller
{
    public function pay(Request $request, Rental $rental)
    {
        try {
            if ($request->payment_method === 'transfer') {
                $request->validate([
                    'amount' => 'required|numeric|min:1',
                    'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:2048',
                    'notes' => 'nullable|string',
                    'extension_id' => 'nullable|exists:rental_extensions,id'
                ]);

                // Validasi jumlah pembayaran
                $totalPaid = $rental->payments()->where('status', 'success')->sum('amount');
                $remainingAmount = $rental->total_price - $totalPaid;
                
                if ($request->amount > $remainingAmount) {
                    return back()->with('error', 'Jumlah pembayaran melebihi sisa tagihan');
                }

                // Upload bukti pembayaran
                $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');

                // Buat payment record
                $payment = Payment::create([
                    'rental_id' => $rental->id,
                    'extension_id' => $request->extension_id,
                    'payment_code' => 'PAY' . date('Ymd') . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT),
                    'amount' => $request->amount,
                    'payment_method' => 'transfer',
                    'payment_proof' => $proofPath,
                    'status' => 'pending',
                    'notes' => $request->notes
                ]);

                // Kirim email notifikasi ke user
                $additionalMessage = "Pembayaran sebesar Rp " . number_format($request->amount) . " sedang menunggu verifikasi admin.";
                Mail::to($rental->user->email)
                    ->send(new RentalStatusMail($rental, 'payment_pending', $additionalMessage));
                
                // Kirim email notifikasi ke admin
                $adminEmails = User::where('role', 'admin')->where('is_active', 1)->pluck('email')->toArray();
                Mail::to($adminEmails)->send(
                    new RentalStatusMail($rental, 'payment_pending', "Ada pembayaran baru sebesar Rp " . number_format($request->amount) . " yang menunggu verifikasi.")
                );

                return redirect()->route('rentals.index')
                    ->with('success', 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin');
            }

            // Jika bukan transfer, lanjutkan ke Midtrans
            return $this->getSnapToken($rental);
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
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
            'extension_id' => 'nullable|exists:rental_extensions,id',
            'amount' => 'required|numeric|min:1',
            'payment_method' => 'required|in:transfer,cash',
            'payment_proof' => 'required_if:payment_method,transfer|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            DB::beginTransaction();

            // Upload bukti pembayaran
            $proofPath = null;
            if ($request->hasFile('payment_proof')) {
                $proofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Buat payment record
            $payment = Payment::create([
                'rental_id' => $request->rental_id,
                'extension_id' => $request->extension_id,
                'payment_code' => 'PAY' . date('Ymd') . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT),
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_proof' => $proofPath,
                'status' => 'pending',
                'notes' => $request->notes
            ]);

            // Update status extension jika ini pembayaran extension
            if ($request->extension_id) {
                RentalExtension::find($request->extension_id)->update([
                    'payment_status' => 'pending'
                ]);
            }

            DB::commit();
            return back()->with('success', 'Bukti pembayaran berhasil dikirim dan menunggu verifikasi admin');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function verifyPayment(Payment $payment)
    {
        try {
            DB::beginTransaction();
            
            // Update status pembayaran menjadi success
            $payment->status = 'success';
            $payment->save();

            $rental = Rental::find($payment->rental_id);

            // Cek apakah ini pembayaran extension
            if ($payment->extension_id) {
                $extension = RentalExtension::findOrFail($payment->extension_id);
                
                // Update tanggal selesai rental
                $rental->update([
                    'end_date' => $extension->end_date,
                    'total_days' => $rental->total_days + $extension->additional_days,
                    'total_price' => $rental->total_price + $extension->additional_price
                ]);

                // Update status extension
                $extension->update([
                    'payment_status' => 'paid',
                    'paid_at' => now()
                ]);

                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'extension_paid',
                    "Pembayaran perpanjangan sewa telah diverifikasi. Masa sewa diperpanjang hingga " . 
                    $extension->end_date->format('d/m/Y'))
                );
                
                // Kirim email notifikasi ke admin
                $adminEmails = User::where('role', 'admin')->where('is_active', 1)->pluck('email')->toArray();
                Mail::to($adminEmails)->send(
                    new RentalStatusMail($rental, 'extension_paid',
                    "Pembayaran perpanjangan dari " . $rental->user->firstname . ' ' . $rental->user->lastname . 
                    " telah diverifikasi. Masa sewa diperpanjang hingga " . $extension->end_date->format('d/m/Y'))
                );
            } else {
                // Pembayaran rental normal
                // Hitung total pembayaran yang sudah success
                $totalPaid = $rental->payments()
                    ->where('status', 'success')
                    ->sum('amount');
                
                // Update status pembayaran rental
                if ($totalPaid >= $rental->total_price) {
                    $rental->payment_status = 'paid';
                    $rental->rental_status = 'ongoing';
                    $rental->status = 'aktif';
                } else {
                    $rental->payment_status = 'partially_paid';
                }
                $rental->save();

                // Kirim email notifikasi pembayaran normal ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " telah diverifikasi.")
                );
                
                // Kirim email notifikasi ke admin
                $adminEmails = User::where('role', 'admin')->where('is_active', 1)->pluck('email')->toArray();
                Mail::to($adminEmails)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran dari " . $rental->user->firstname . ' ' . $rental->user->lastname . 
                    " sebesar Rp " . number_format($payment->amount) . " telah diverifikasi.")
                );
            }

            DB::commit();
            return redirect()->back()->with('success', 'Pembayaran berhasil diverifikasi');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Payment Verification Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal memverifikasi pembayaran: ' . $e->getMessage());
        }
    }

    public function startPayment(Rental $rental)
    {
        try {
            // Validasi rental
            if ($rental->user_id !== auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses ke rental ini');
            }

            // Validasi status rental
            if ($rental->payment_status === 'paid') {
                throw new \Exception('Rental ini sudah lunas');
            }

            // Hitung sisa pembayaran
            $totalPaid = $rental->payments()->where('status', 'success')->sum('amount');
            $remainingAmount = $rental->total_price - $totalPaid;

            if ($remainingAmount <= 0) {
                throw new \Exception('Tidak ada sisa pembayaran');
            }

            DB::beginTransaction();

            // Konfigurasi Midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = env('MIDTRANS_IS_SANITIZED', true);
            \Midtrans\Config::$is3ds = env('MIDTRANS_IS_3DS', true);

            // Buat payment record
            $payment = Payment::create([
                'rental_id' => $rental->id,
                'payment_code' => 'PAY' . date('Ymd') . str_pad(Payment::count() + 1, 4, '0', STR_PAD_LEFT),
                'amount' => $remainingAmount, // Gunakan sisa pembayaran
                'payment_method' => 'midtrans',
                'status' => 'pending'
            ]);

            $transaction_details = array(
                'order_id' => $payment->payment_code,
                'gross_amount' => (int) $payment->amount
            );

            $customer_details = array(
                'first_name' => $rental->user->firstname,
                'last_name' => $rental->user->lastname,
                'email' => $rental->user->email,
                'phone' => $rental->user->phone ?? '08123456789'
            );

            $transaction_data = array(
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details
            );

            $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);
            
            DB::commit();
            
            return response()->json([
                'snap_token' => $snapToken,
                'payment' => $payment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Payment Error: ' . $e->getMessage());
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function success()
    {
        return view('customer.payments.success');
    }

    public function pending()
    {
        return view('customer.payments.pending');
    }

    public function error()
    {
        return view('customer.payments.error');
    }

    public function getSnapToken(Request $request, Rental $rental)
    {
        try {
            // Set konfigurasi midtrans
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$clientKey = env('MIDTRANS_CLIENT_KEY');
            \Midtrans\Config::$merchantId = env('MIDTRANS_MERCHANT_ID');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            $remainingAmount = $rental->total_price - $rental->payments()->where('status', 'success')->sum('amount');

            // Buat transaksi
            $transaction_details = [
                'order_id' => 'PAY-' . time(),
                'gross_amount' => (int) $remainingAmount
            ];

            // Data pelanggan
            $customer_details = [
                'first_name' => $rental->user->firstname,
                'last_name' => $rental->user->lastname,
                'email' => $rental->user->email,
                'phone' => $rental->user->phone ?? ''
            ];

            // Item details
            $item_details = [
                [
                    'id' => $rental->rental_code,
                    'price' => (int) $remainingAmount,
                    'quantity' => 1,
                    'name' => 'Pembayaran Sewa Bus ' . $rental->bus->name,
                    'merchant_name' => 'PO Bis Ekasari'
                ]
            ];

            $transaction_data = [
                'transaction_details' => $transaction_details,
                'customer_details' => $customer_details,
                'item_details' => $item_details,
                'enabled_payments' => ['credit_card', 'mandiri_clickpay', 'bca_klikbca', 'bca_klikpay', 'bri_epay', 'echannel', 'permata_va', 'bca_va', 'bni_va', 'bri_va', 'other_va', 'gopay', 'indomaret'],
                'credit_card' => [
                    'secure' => true,
                    'channel' => 'migs',
                    'bank' => 'bca',
                    'save_card' => true
                ]
            ];

            // Dapatkan Snap Token
            $snapToken = \Midtrans\Snap::getSnapToken($transaction_data);

            return response()->json([
                'snap_token' => $snapToken,
                'rental' => $rental
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Tambahkan method ini untuk menangani notifikasi dari Midtrans
    public function handleMidtransNotification(Request $request)
    {
        try {
            DB::beginTransaction();

            $notif = new \Midtrans\Notification();
            
            $transaction = $notif->transaction_status;
            $type = $notif->payment_type;
            $order_id = $notif->order_id;
            $fraud = $notif->fraud_status;
            
            \Log::info('Midtrans Notification', ['order_id' => $order_id, 'status' => $transaction]);
            
            // Cari payment berdasarkan order_id (payment_code)
            $payment = Payment::where('payment_code', $order_id)->first();
            
            if (!$payment) {
                \Log::error('Payment not found: ' . $order_id);
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }
            
            $rental = Rental::find($payment->rental_id);
            
            if ($transaction == 'capture') {
                // Untuk kartu kredit
                if ($type == 'credit_card'){
                    if($fraud == 'challenge'){
                        $payment->status = 'challenge';
                    } else {
                        $payment->status = 'success';
                        $rental->update([
                            'payment_status' => 'paid',
                            'rental_status' => 'ongoing',
                            'status' => 'aktif'
                        ]);
                    }
                }
            } 
            elseif ($transaction == 'settlement') {
                // Pembayaran berhasil
                $payment->status = 'success';
                
                // Update status rental langsung menjadi paid dan ongoing
                $rental->update([
                    'payment_status' => 'paid',
                    'rental_status' => 'ongoing',
                    'status' => 'aktif'
                ]);
                
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " telah berhasil melalui " . ucfirst($type))
                );
                
                // Kirim email notifikasi ke admin
                $adminEmails = User::where('role', 'admin')->where('is_active', 1)->pluck('email')->toArray();
                Mail::to($adminEmails)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran dari " . $rental->user->firstname . ' ' . $rental->user->lastname . 
                    " sebesar Rp " . number_format($payment->amount) . " telah berhasil melalui " . ucfirst($type))
                );
            } 
            elseif($transaction == 'pending'){
                // Pembayaran pending
                $payment->status = 'pending';
                
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_pending', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " sedang menunggu pembayaran Anda melalui " . ucfirst($type))
                );
            } 
            elseif ($transaction == 'deny' || $transaction == 'expire' || $transaction == 'cancel') {
                // Pembayaran ditolak/batal/expire
                $payment->status = 'failed';
                
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_failed', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " gagal/dibatalkan. Status: " . ucfirst($transaction))
                );
            }
            
            $payment->save();
            DB::commit();
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Notification Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePaymentStatus(Request $request)
    {
        try {
            DB::beginTransaction();
            
            $rentalId = $request->rental_id;
            $status = $request->status;
            $result = $request->result;
            
            // Cari rental dan payment terkait
            $rental = Rental::findOrFail($rentalId);
            $payment = Payment::where('rental_id', $rentalId)
                             ->where('payment_method', 'midtrans')
                             ->where('status', 'pending')
                             ->latest()
                             ->first();
            
            if (!$payment) {
                return response()->json(['status' => 'error', 'message' => 'Payment not found'], 404);
            }
            
            // Update status payment berdasarkan status dari Midtrans
            if ($status === 'success') {
                $payment->status = 'success';
                
                // Update status rental langsung menjadi paid dan ongoing
                $rental->update([
                    'payment_status' => 'paid',
                    'rental_status' => 'ongoing',
                    'status' => 'aktif'
                ]);
                
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " telah berhasil")
                );
                
                // Kirim email notifikasi ke admin
                $adminEmails = User::where('role', 'admin')->where('is_active', 1)->pluck('email')->toArray();
                Mail::to($adminEmails)->send(
                    new RentalStatusMail($rental, 'payment_success', 
                    "Pembayaran dari " . $rental->user->firstname . ' ' . $rental->user->lastname . 
                    " sebesar Rp " . number_format($payment->amount) . " telah berhasil")
                );
            } 
            elseif ($status === 'pending') {
                $payment->status = 'pending';
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_pending', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " sedang dalam proses")
                );
            } 
            elseif ($status === 'error') {
                $payment->status = 'failed';
                
                // Kirim email notifikasi ke user
                Mail::to($rental->user->email)->send(
                    new RentalStatusMail($rental, 'payment_failed', 
                    "Pembayaran sebesar Rp " . number_format($payment->amount) . " gagal. Silakan coba lagi.")
                );
            }
            
            $payment->save();
            DB::commit();
            
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Midtrans Status Update Error: ' . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}