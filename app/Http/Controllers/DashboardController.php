<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function ownerDashboard()
    {
        if (auth()->user()->role !== 'owner') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $data = [
            'totalBis' => Bus::count(),
            'bisTersedia' => Bus::where('status', 'available')->count(),
            'bisDisewa' => Bus::where('status', 'rented')->count(),
            'pendapatanBulan' => Payment::whereMonth('created_at', now()->month)
                                      ->where('status', 'verified')
                                      ->sum('amount'),
            'chartData' => [
                'labels' => $this->getLast7Days(),
                'data' => $this->getRentalData()
            ],
            'topBuses' => $this->getTopBuses(),
            'topDrivers' => $this->getTopDrivers()
        ];

        return view('owner.dashboard', $data);
    }

    public function adminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $data = [
            'totalBis' => Bus::count(),
            'bisTersedia' => Bus::where('status', 'available')->count(),
            'bisDisewa' => Bus::where('status', 'rented')->count(),
            'bisMaintenance' => Bus::where('status', 'maintenance')->count(),
            'latestRentals' => Rental::with(['user', 'bus'])
                                    ->latest()
                                    ->take(5)
                                    ->get(),
            'pendingPayments' => Payment::with(['rental.user'])
                                      ->where('status', 'pending')
                                      ->latest()
                                      ->take(5)
                                      ->get()
        ];

        return view('admin.dashboard', $data);
    }

    public function customerDashboard()
    {
        if (auth()->user()->role !== 'customer') {
            return redirect('/')->with('error', 'Unauthorized access');
        }

        $userId = auth()->id();
        
        $data = [
            'activeRental' => Rental::with(['bus', 'driver', 'payment'])
                                   ->where('user_id', $userId)
                                   ->where('status', 'active')
                                   ->first(),
            'rentalHistory' => Rental::with(['bus', 'ratings'])
                                    ->where('user_id', $userId)
                                    ->where('status', '!=', 'active')
                                    ->latest()
                                    ->get()
        ];

        return view('customer.dashboard', $data);
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