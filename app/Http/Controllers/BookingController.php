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
        try {
            // Debug input
            $input = $request->all();
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Input: " . json_encode($input) . "\n", 
                FILE_APPEND);

            $request->validate([
                'bus_id' => 'required|exists:buses,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'destination' => 'required|string|max:255',
                'notes' => 'nullable|string'
            ]);

            $bus = Bus::findOrFail($request->bus_id);

            if ($bus->status !== 'tersedia') {
                return back()->with('error', 'Bus tidak tersedia untuk disewa');
            }

            // Parse tanggal dengan cara yang berbeda
            $startDate = Carbon::parse($request->start_date);
            $endDate = Carbon::parse($request->end_date);

            // Debug tanggal
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Dates: start={$startDate}, end={$endDate}\n", 
                FILE_APPEND);

            // Hitung total hari dengan cara yang berbeda
            $totalDays = $startDate->copy()->startOfDay()
                                  ->diffInDays($endDate->copy()->startOfDay()) + 1;

            // Debug perhitungan
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Calculation: days={$totalDays}\n", 
                FILE_APPEND);

            // Hitung total harga
            $totalPrice = abs($bus->price_per_day * $totalDays);

            // Debug harga
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Price: per_day={$bus->price_per_day}, total={$totalPrice}\n", 
                FILE_APPEND);

            // Buat booking
            $booking = Booking::create([
                'user_id' => auth()->id(),
                'bus_id' => $bus->id,
                'start_date' => $startDate->format('Y-m-d'),
                'end_date' => $endDate->format('Y-m-d'),
                'destination' => $request->destination,
                'notes' => $request->notes,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'total_days' => $totalDays
            ]);

            // Debug booking
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Booking created: " . json_encode($booking->toArray()) . "\n", 
                FILE_APPEND);

            return redirect()
                ->route('bookings.show', $booking)
                ->with('success', 'Pemesanan berhasil dibuat! Silahkan tunggu konfirmasi dari admin.');

        } catch (\Exception $e) {
            // Debug error
            file_put_contents(storage_path('logs/booking_debug.log'), 
                date('Y-m-d H:i:s') . " Error: {$e->getMessage()}\n{$e->getTraceAsString()}\n", 
                FILE_APPEND);
            
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