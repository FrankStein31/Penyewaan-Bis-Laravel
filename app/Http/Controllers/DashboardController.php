<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function ownerDashboard()
    {
        if (auth()->user()->role !== 'owner') {
            return redirect('/')->with('error', 'Akses tidak diizinkan');
        }

        $data = [
            'totalBis' => Bus::count(),
            'bisTersedia' => Bus::where('status', 'tersedia')->count(),
            'bisDisewa' => Bus::where('status', 'disewa')->count(),
            'pendapatanBulan' => Payment::whereMonth('created_at', now()->month)
                                      ->where('status', 'verified')
                                      ->sum('amount'),
            'chartData' => [
                'labels' => $this->getLast7Days(),
                'data' => $this->getRentalData()
            ],
            'topBuses' => $this->getTopBuses(),
            'topDrivers' => $this->getTopDrivers(),
            'latestRentals' => Rental::with(['user', 'bus'])
                                    ->latest()
                                    ->take(5)
                                    ->get()
        ];

        return view('owner.dashboard', $data);
    }

    public function adminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Akses tidak diizinkan');
        }

        $data = [
            'totalBis' => Bus::count(),
            'bisTersedia' => Bus::where('status', 'tersedia')->count(),
            'bisDisewa' => Bus::where('status', 'disewa')->count(),
            'bisMaintenance' => Bus::where('status', 'maintenance')->count(),
            'latestRentals' => Rental::with(['user', 'bus', 'driver'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
            'pendingPayments' => Payment::with(['rental.user'])
                                      ->where('status', 'pending')
                                      ->latest()
                                      ->take(5)
                                      ->get(),
            'chartData' => [
                'labels' => $this->getLast7Days(),
                'data' => $this->getRentalData()
            ]
        ];

        return view('admin.dashboard', $data);
    }

    public function customerDashboard()
    {
        try {
            // Ambil semua rental untuk user yang login
            $rentals = Rental::where('user_id', auth()->id())
                            ->with(['payments'])
                            ->latest()
                            ->get();

            return view('customer.dashboard', compact('rentals'));

        } catch (\Exception $e) {
            \Log::error('Dashboard Error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat memuat dashboard');
        }
    }

    private function getLast7Days()
    {
        return collect(range(6, 0))->map(function($days) {
            return now()->subDays($days)->format('d/m');
        })->toArray();
    }

    private function getRentalData()
    {
        return collect(range(6, 0))->map(function($days) {
            return Rental::whereDate('created_at', now()->subDays($days))->count();
        })->toArray();
    }

    private function getTopBuses()
    {
        $topBuses = Bus::withCount('rentals')
                      ->orderBy('rentals_count', 'desc')
                      ->take(5)
                      ->get();

        return [
            'labels' => $topBuses->pluck('plate_number')->toArray(),
            'data' => $topBuses->pluck('rentals_count')->toArray()
        ];
    }

    private function getTopDrivers()
    {
        return Driver::withCount('rentals')
                    ->withAvg('ratings', 'rating')
                    ->orderBy('ratings_avg_rating', 'desc')
                    ->take(5)
                    ->get();
    }
}