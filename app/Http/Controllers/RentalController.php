<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Bus;
use App\Models\User;
use App\Models\Driver;
use App\Models\Conductor;
use App\Models\Payment;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('user_id', auth()->id())
                        ->with(['bus', 'driver', 'conductor', 'payments'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                          
        return view('pages.rentals.index', compact('rentals'));
    }

    public function store(Request $request)
    {
        try {
            $validationRules = [
                'bus_id' => 'required|exists:buses,id',
                'driver_id' => 'required|exists:drivers,id',
                'conductor_id' => 'required|exists:conductors,id',
                'start_date' => 'required|date|after_or_equal:today',
                'rental_package' => 'required|in:day,trip',
                'pickup_location' => 'required|string',
                'destination' => 'required|string',
                'notes' => 'nullable|string'
            ];

            // Tambahkan validasi end_date jika paket trip
            if ($request->rental_package === 'trip') {
                $validationRules['end_date'] = 'required|date|after:start_date';
            }

            $request->validate($validationRules);

            $bus = Bus::findOrFail($request->bus_id);
            $driver = Driver::findOrFail($request->driver_id);
            $conductor = Conductor::findOrFail($request->conductor_id);

            // Cek ketersediaan
            if ($bus->status !== 'tersedia') {
                return back()->with('error', 'Bus tidak tersedia untuk disewa');
            }
            if ($driver->status !== 'available' || $conductor->status !== 'available') {
                return back()->with('error', 'Driver atau conductor tidak tersedia');
            }

            // Parse tanggal dengan benar
            $startDate = Carbon::parse($request->start_date);
            $endDate = $request->rental_package === 'day' ? 
                $startDate->copy()->addDay() : 
                Carbon::parse($request->end_date);

            // Hitung total hari berdasarkan paket
            if ($request->rental_package === 'day') {
                $totalDays = 1; // Paket day selalu 1 hari
            } else {
                $totalDays = $startDate->diffInDays($endDate) + 1; // Untuk paket trip
            }

            // Hitung total harga
            $totalPrice = $bus->price_per_day * $totalDays;

            // Debug log
            \Log::info('Rental Calculation', [
                'package' => $request->rental_package,
                'start_date' => $startDate->format('Y-m-d H:i'),
                'end_date' => $endDate->format('Y-m-d H:i'),
                'total_days' => $totalDays,
                'price_per_day' => $bus->price_per_day,
                'total_price' => $totalPrice
            ]);

            // Buat rental baru
            $rental = Rental::create([
                'rental_code' => Rental::generateRentalCode(),
                'user_id' => auth()->id(),
                'bus_id' => $bus->id,
                'driver_id' => $driver->id,
                'conductor_id' => $conductor->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'pickup_location' => $request->pickup_location,
                'destination' => $request->destination,
                'total_days' => $totalDays,
                'total_price' => $totalPrice,
                'rental_package' => $request->rental_package,
                'status' => 'pending',
                'rental_status' => 'pending',
                'payment_status' => 'unpaid',
                'notes' => $request->notes
            ]);

            // Update status
            $bus->update(['status' => 'disewa']);
            $driver->update(['status' => 'on_duty']);
            $conductor->update(['status' => 'on_duty']);

            return redirect()
                ->route('rentals.show', $rental)
                ->with('success', 'Pemesanan berhasil dibuat! Silahkan tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            \Log::error('Rental Error: ' . $e->getMessage());
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // public function show(Rental $rental)
    // {
    //     // Pastikan user hanya bisa lihat rentalnya sendiri
    //     if ($rental->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
    //         abort(403);
    //     }

    //     return view('pages.rentals.show', compact('rental'));
    // }

    public function show(Rental $rental)
    {
        if ($rental->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        $rental->load(['bus.armada', 'driver', 'conductor', 'payments']);
        return view('pages.rentals.show', compact('rental'));
    }

    public function cancel(Rental $rental)
    {
        try {
            DB::beginTransaction();
            
            // Debug log
            \Log::info('Attempting to cancel rental: ' . $rental->id);
            
            // Cek apakah rental milik user yang login
            if ($rental->user_id !== auth()->id()) {
                \Log::warning('Unauthorized cancel attempt for rental: ' . $rental->id);
                return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan pesanan ini');
            }

            // Cek apakah status masih pending
            if ($rental->rental_status !== 'pending') {
                \Log::warning('Invalid status for cancellation. Current status: ' . $rental->rental_status);
                return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan');
            }

            // Update status rental
            $rental->update([
                'status' => 'dibatalkan',
                'rental_status' => 'cancelled',
                'payment_status' => 'unpaid'
            ]);

            \Log::info('Rental status updated to cancelled');

            // Kembalikan status bus menjadi tersedia
            if ($rental->bus) {
                $rental->bus->update(['status' => 'tersedia']);
                \Log::info('Bus status updated to tersedia');
            }

            // Kembalikan status driver menjadi available
            if ($rental->driver) {
                $rental->driver->update(['status' => 'available']);
                \Log::info('Driver status updated to available');
            }

            // Kembalikan status conductor menjadi available
            if ($rental->conductor) {
                $rental->conductor->update(['status' => 'available']);
                \Log::info('Conductor status updated to available');
            }

            DB::commit();
            \Log::info('Rental cancellation completed successfully');

            return redirect()
                ->route('customer.rentals.index')
                ->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cancel Rental Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat membatalkan pesanan');
        }
    }

    public function getAvailableCrew(Request $request)
    {
        try {
            $request->validate([
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
            ]);

            $availableDrivers = Driver::where('is_active', true)
                ->where('status', 'available')
                ->whereDoesntHave('rentals', function($query) use ($request) {
                    $query->whereNotIn('rental_status', ['completed', 'cancelled'])
                          ->where(function($q) use ($request) {
                              $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                                ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                          });
                })
                ->select('id', 'name', 'phone', 'license_number')
                ->get()
                ->map(function($driver) {
                    return [
                        'id' => $driver->id,
                        'name' => $driver->name,
                        'phone' => $driver->phone,
                        'license' => $driver->license_number,
                        'rating' => 4.5, // Sementara hardcode dulu
                        'total_trips' => 10 // Sementara hardcode dulu
                    ];
                });

            $availableConductors = Conductor::where('is_active', true)
                ->where('status', 'available')
                ->whereDoesntHave('rentals', function($query) use ($request) {
                    $query->whereNotIn('rental_status', ['completed', 'cancelled'])
                          ->where(function($q) use ($request) {
                              $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                                ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                          });
                })
                ->select('id', 'name', 'phone')
                ->get()
                ->map(function($conductor) {
                    return [
                        'id' => $conductor->id,
                        'name' => $conductor->name,
                        'phone' => $conductor->phone,
                        'rating' => 4.0, // Sementara hardcode dulu
                        'total_trips' => 8 // Sementara hardcode dulu
                    ];
                });

            return response()->json([
                'status' => 'success',
                'drivers' => $availableDrivers,
                'conductors' => $availableConductors
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function adminIndex()
    {
        try {
            \Log::info('Accessing admin rental index');  // Log akses
            
            $rentals = Rental::with(['user', 'bus', 'driver', 'conductor', 'payments'])
                            ->latest()
                            ->get();
            
            \Log::info('Successfully retrieved rentals: ' . $rentals->count());  // Log jumlah data
            
            return view('admin.rentals.index', compact('rentals'));
        } catch (\Exception $e) {
            \Log::error('Admin Rental Index Error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());  // Log stack trace
            return back()->with('error', 'Terjadi kesalahan saat memuat data rental');
        }
    }

    public function adminShow(Rental $rental)
    {
        try {
            $rental->load([
                'user', 
                'bus', 
                'driver', 
                'conductor', 
                'payments' => function($query) {
                    $query->latest();
                }
            ]);
            
            // Hitung total pembayaran yang sudah diverifikasi
            $totalPaid = $rental->payments()
                               ->where('status', 'success')
                               ->sum('amount');
                               
            $remainingAmount = $rental->total_price - $totalPaid;
            
            return view('admin.rentals.show', compact('rental', 'totalPaid', 'remainingAmount'));
        } catch (\Exception $e) {
            \Log::error('Admin Rental Show Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat detail rental');
        }
    }

    public function updateStatus(Request $request, Rental $rental)
    {
        try {
            DB::beginTransaction();
            
            $oldStatus = $rental->rental_status;
            $newStatus = $request->rental_status;

            // Validasi perubahan status
            if ($newStatus === 'ongoing') {
                if ($rental->payment_status === 'unpaid') {
                    return back()->with('error', 'Status tidak dapat diubah ke ongoing karena belum ada pembayaran sama sekali. Customer harus melakukan pembayaran minimal parsial.');
                }

                if ($rental->rental_status !== 'confirmed') {
                    return back()->with('error', 'Status hanya bisa diubah ke ongoing setelah pesanan dikonfirmasi dan ada pembayaran.');
                }
            }

            // Update rental status
            $rental->update([
                'rental_status' => $newStatus,
                'status' => $this->mapRentalStatus($newStatus)
            ]);

            // Update related resources based on status
            if ($newStatus === 'cancelled' || $newStatus === 'completed') {
                // Reset status resources
                if ($rental->bus) {
                    $rental->bus->update(['status' => 'tersedia']);
                }
                if ($rental->driver) {
                    $rental->driver->update(['status' => 'available']);
                }
                if ($rental->conductor) {
                    $rental->conductor->update(['status' => 'available']);
                }
            }

            DB::commit();
            
            $message = match($newStatus) {
                'confirmed' => "Status pesanan berhasil diubah menjadi CONFIRMED.\nCustomer harus melakukan pembayaran (minimal parsial) untuk melanjutkan ke status ongoing.",
                'ongoing' => $rental->payment_status === 'partial' ? 
                    "Status pesanan berhasil diubah menjadi ONGOING.\nPeringatan: Masih ada sisa pembayaran yang belum lunas!" :
                    "Status pesanan berhasil diubah menjadi ONGOING.\nPerjalanan dapat dimulai!",
                'completed' => "Status pesanan berhasil diubah menjadi COMPLETED.\nPesanan telah selesai.",
                'cancelled' => "Status pesanan berhasil diubah menjadi CANCELLED.\nSemua resource telah direset.",
                default => "Status pesanan berhasil diubah menjadi " . strtoupper($newStatus)
            };

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Rental Status Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui status: ' . $e->getMessage());
        }
    }

    public function updatePayment(Request $request, Rental $rental)
    {
        try {
            DB::beginTransaction();
            
            $request->validate([
                'payment_status' => 'required|in:unpaid,partial,paid',
                'amount' => 'required|numeric|min:0',
                'payment_method' => 'required|string',
                'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
                'notes' => 'nullable|string'
            ]);

            // Validasi status rental
            if ($rental->rental_status !== 'confirmed') {
                return back()->with('error', 'Pembayaran hanya dapat diproses untuk pesanan yang sudah dikonfirmasi');
            }

            // Update payment status di rental
            $rental->update([
                'payment_status' => $request->payment_status
            ]);

            // Generate payment code
            $paymentCode = 'PAY-' . strtoupper(uniqid());

            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $paymentProofPath = $request->file('payment_proof')->store('payment-proofs', 'public');
            }

            // Create payment record
            $rental->payments()->create([
                'payment_code' => $paymentCode,
                'amount' => $request->amount,
                'payment_method' => $request->payment_method,
                'payment_proof' => $paymentProofPath,
                'status' => 'success',
                'notes' => $request->notes
            ]);

            // Jika pembayaran lunas atau parsial, otomatis ubah status rental menjadi ongoing
            if ($request->payment_status === 'paid' || $request->payment_status === 'partial') {
                $rental->update([
                    'rental_status' => 'ongoing',
                    'status' => 'aktif'
                ]);
            }

            DB::commit();

            $message = match($request->payment_status) {
                'paid' => 'Pembayaran lunas. Status rental diubah menjadi ongoing.',
                'partial' => 'Pembayaran parsial berhasil dicatat. Status rental diubah menjadi ongoing.',
                default => 'Status pembayaran berhasil diperbarui'
            };

            return back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Update Payment Status Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memperbarui pembayaran');
        }
    }

    private function mapRentalStatus($rentalStatus)
    {
        $statusMap = [
            'pending' => 'pending',
            'confirmed' => 'aktif',
            'ongoing' => 'aktif',
            'completed' => 'selesai',
            'cancelled' => 'dibatalkan'
        ];

        return $statusMap[$rentalStatus] ?? 'pending';
    }

    public function requests()
    {
        $requests = Rental::where('rental_status', 'pending')
                         ->with(['user', 'bus', 'driver', 'conductor'])
                         ->latest()
                         ->get();
                         
        return view('admin.rentals.requests', compact('requests'));
    }

    public function showRequest(Rental $rental)
    {
        if ($rental->rental_status !== 'pending') {
            return redirect()->route('admin.rentals.show', $rental);
        }
        
        $rental->load(['user', 'bus', 'driver', 'conductor']);
        return view('admin.rentals.show-request', compact('rental'));
    }

    public function confirm(Rental $rental)
    {
        try {
            DB::beginTransaction();

            $rental->update([
                'rental_status' => 'confirmed',
                'status' => 'aktif'
            ]);

            DB::commit();
            return back()->with('success', 'Rental berhasil dikonfirmasi');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Confirm Rental Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat mengkonfirmasi rental');
        }
    }

    public function complete(Rental $rental)
    {
        try {
            DB::beginTransaction();

            $rental->update([
                'rental_status' => 'completed',
                'status' => 'selesai'
            ]);

            // Update status resources
            if ($rental->bus) {
                $rental->bus->update(['status' => 'tersedia']);
            }
            if ($rental->driver) {
                $rental->driver->update(['status' => 'available']);
            }
            if ($rental->conductor) {
                $rental->conductor->update(['status' => 'available']);
            }

            DB::commit();
            return back()->with('success', 'Rental berhasil diselesaikan');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Complete Rental Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menyelesaikan rental');
        }
    }
} 