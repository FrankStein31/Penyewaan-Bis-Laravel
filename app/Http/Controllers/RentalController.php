<?php

namespace App\Http\Controllers;

use App\Models\Rental;
use App\Models\Bus;
use App\Models\User;
use App\Models\Driver;
use App\Models\Conductor;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::where('user_id', auth()->id())
                        ->with(['bus', 'driver', 'conductor'])
                        ->orderBy('created_at', 'desc')
                        ->get();
                          
        return view('pages.rentals.index', compact('rentals'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'bus_id' => 'required|exists:buses,id',
                'driver_id' => 'required|exists:drivers,id',
                'conductor_id' => 'required|exists:conductors,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'pickup_location' => 'required|string',
                'destination' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            $bus = Bus::findOrFail($request->bus_id);
            $driver = Driver::findOrFail($request->driver_id);
            $conductor = Conductor::findOrFail($request->conductor_id);

            // Cek apakah bus tersedia
            if ($bus->status !== 'tersedia') {
                return back()->with('error', 'Bus tidak tersedia untuk disewa');
            }

            // Cek apakah driver dan conductor tersedia
            if ($driver->status !== 'available' || $conductor->status !== 'available') {
                return back()->with('error', 'Driver atau conductor tidak tersedia');
            }

            // Hitung total hari dan harga
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            
            // Perbaiki perhitungan total hari
            $totalDays = $endDate->diffInDays($startDate);
            if ($totalDays == 0) {
                $totalDays = 1; // Minimal 1 hari
            }
            
            $totalPrice = $bus->price_per_day * $totalDays;

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

    public function show(Rental $rental)
    {
        // Pastikan user hanya bisa lihat rentalnya sendiri
        if ($rental->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('pages.rentals.show', compact('rental'));
    }

    public function cancel(Rental $rental)
    {
        try {
            // Cek apakah rental milik user yang login
            if ($rental->user_id !== auth()->id()) {
                return back()->with('error', 'Anda tidak memiliki akses untuk membatalkan pesanan ini');
            }

            // Cek apakah status masih pending
            if ($rental->rental_status !== 'pending') {
                return back()->with('error', 'Hanya pesanan dengan status pending yang dapat dibatalkan');
            }

            // Update status rental
            $rental->update([
                'rental_status' => 'cancelled',
                'status' => 'cancelled',
                'payment_status' => 'cancelled'
            ]);

            // Kembalikan status bus, driver dan conductor
            $rental->bus->update(['status' => 'tersedia']);
            $rental->driver->update(['status' => 'available']);
            $rental->conductor->update(['status' => 'available']);

            return redirect()
                ->route('rentals.index')
                ->with('success', 'Pesanan berhasil dibatalkan');

        } catch (\Exception $e) {
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
            $rentals = Rental::with([
                'user', 
                'bus', 
                'driver', 
                'conductor', 
                'payment'
            ])->latest()->get();
            
            return view('pages.rentals.admin.index', compact('rentals'));
        } catch (\Exception $e) {
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
                'payment'
            ]);
            
            return view('pages.rentals.admin.show', compact('rental'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memuat detail rental');
        }
    }
} 