<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Bus;
use Illuminate\Http\Request;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->id())
                          ->with(['bus'])
                          ->orderBy('created_at', 'desc')
                          ->get();
                          
        return view('pages.bookings.index', compact('bookings'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'bus_id' => 'required|exists:buses,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'destination' => 'required|string|max:255',
            'notes' => 'nullable|string'
        ]);

        try {
            $bus = Bus::findOrFail($request->bus_id);

            // Cek apakah bus tersedia
            if ($bus->status !== 'tersedia') {
                return back()->with('error', 'Bus tidak tersedia untuk disewa');
            }

            // Cek apakah ada booking lain di rentang tanggal yang sama
            $existingBooking = Booking::where('bus_id', $bus->id)
                ->where('status', '!=', 'cancelled')
                ->where(function($query) use ($request) {
                    $query->whereBetween('start_date', [$request->start_date, $request->end_date])
                          ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
                })->exists();

            if ($existingBooking) {
                return back()->with('error', 'Bus sudah dipesan untuk tanggal tersebut');
            }

            // Hitung total hari dan harga
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);
            $totalDays = $endDate->diffInDays($startDate) + 1;
            $totalPrice = $bus->price_per_day * $totalDays;

            // Buat booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'bus_id' => $bus->id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'destination' => $request->destination,
                'notes' => $request->notes,
                'total_price' => $totalPrice,
                'status' => 'pending'
            ]);

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Pemesanan berhasil dibuat! Silahkan tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function show(Booking $booking)
    {
        // Pastikan user hanya bisa lihat bookingnya sendiri
        if ($booking->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            abort(403);
        }

        return view('pages.bookings.show', compact('booking'));
    }

    public function cancel(Booking $booking)
    {
        // Pastikan user hanya bisa cancel bookingnya sendiri
        if ($booking->user_id !== auth()->id()) {
            abort(403);
        }

        // Pastikan booking masih bisa dicancel (masih pending)
        if ($booking->status !== 'pending') {
            return back()->with('error', 'Booking tidak dapat dibatalkan');
        }

        $booking->update(['status' => 'cancelled']);

        return redirect()
            ->route('bookings.index')
            ->with('success', 'Booking berhasil dibatalkan');
    }
} 