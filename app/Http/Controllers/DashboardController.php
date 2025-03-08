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

        // Statistik Umum
        $data = [
            'totalArmada' => \App\Models\Armada::count(),
            'totalBis' => Bus::count(),
            'bisTersedia' => Bus::where('status', 'tersedia')->count(),
            'bisDisewa' => Bus::where('status', 'disewa')->count(),
            'bisMaintenance' => Bus::where('status', 'maintenance')->count(),
            
            // Pendapatan
            'pendapatanHariIni' => Payment::whereDate('created_at', today())
                                        ->where('status', 'verified')
                                        ->sum('amount'),
            'pendapatanBulan' => Payment::whereMonth('created_at', now()->month)
                                      ->where('status', 'verified')
                                      ->sum('amount'),
            'pendapatanTahun' => Payment::whereYear('created_at', now()->year)
                                      ->where('status', 'verified')
                                      ->sum('amount'),
            
            // Statistik per Armada
            'statistikArmada' => $this->getArmadaStatistics(),
            
            // Grafik Pendapatan dan Pemesanan
            'chartPendapatan' => $this->getRevenueChart(),
            'chartPemesanan' => $this->getBookingChart(),
            'topBuses' => $this->getTopBuses(),
            'topDrivers' => $this->getTopDrivers(),
            
            // Performa Armada
            'performaArmada' => $this->getArmadaPerformance(),
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
        $topBuses = Bus::with('armada')
                      ->withCount('rentals')
                      ->orderBy('rentals_count', 'desc')
                      ->take(5)
                      ->get();

        return [
            'labels' => $topBuses->pluck('plate_number')->toArray(),
            'data' => $topBuses->pluck('rentals_count')->toArray(),
            'armada' => $topBuses->pluck('armada.nama_armada')->toArray()
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

    private function getArmadaStatistics()
    {
        return \App\Models\Armada::withCount(['buses' => function($query) {
            $query->where('is_active', true);
        }])
        ->withCount(['buses as buses_tersedia' => function($query) {
            $query->where('status', 'tersedia')->where('is_active', true);
        }])
        ->withCount(['buses as buses_disewa' => function($query) {
            $query->where('status', 'disewa');
        }])
        ->withCount(['buses as buses_maintenance' => function($query) {
            $query->where('status', 'maintenance');
        }])
        ->get();
    }

    private function getRevenueChart()
    {
        // Ambil data 12 bulan terakhir
        $revenues = Payment::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(amount) as total')
            )
            ->where('status', 'success') // sesuaikan dengan enum di tabel
            ->where('created_at', '>=', now()->subMonths(11)->startOfMonth())
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Debug
        \Log::info('Revenue Data:', ['data' => $revenues->toArray()]);

        // Generate label dan data untuk 12 bulan
        $data = collect();
        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthRevenue = $revenues->first(function($item) use ($date) {
                return $item->year == $date->year && $item->month == $date->month;
            });

            $data->push([
                'month' => $date->format('M Y'),
                'revenue' => $monthRevenue ? (float)$monthRevenue->total : 0
            ]);
        }

        // Debug
        \Log::info('Chart Data:', ['data' => $data->toArray()]);

        return [
            'labels' => $data->pluck('month')->toArray(),
            'data' => $data->pluck('revenue')->toArray()
        ];
    }

    private function getLast12Months()
    {
        return collect(range(11, 0))->map(function($month) {
            return now()->subMonths($month)->format('M Y');
        })->toArray();
    }

    private function getRentalDataMonthly()
    {
        return collect(range(11, 0))->map(function($month) {
            $date = now()->subMonths($month);
            return Rental::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count();
        })->toArray();
    }

    private function getArmadaPerformance()
    {
        return \App\Models\Armada::with(['buses' => function($query) {
            $query->withCount('rentals')
                  ->withSum(['rentals' => function($query) {
                      $query->join('payments', 'rentals.id', '=', 'payments.rental_id')
                            ->where('payments.status', 'verified')
                            ->select(DB::raw('COALESCE(SUM(payments.amount), 0)'));
                  }], 'payments.amount as revenue');
        }])
        ->get()
        ->map(function($armada) {
            $totalRentals = $armada->buses->sum('rentals_count');
            $totalRevenue = $armada->buses->sum('revenue') ?? 0;
            $activeCount = $armada->buses->where('is_active', true)->count();
            
            return [
                'nama_armada' => $armada->nama_armada,
                'total_bus' => $armada->buses->count(),
                'bus_aktif' => $activeCount,
                'total_sewa' => $totalRentals,
                'pendapatan' => $totalRevenue,
                'rata_rata_pendapatan' => $activeCount > 0 ? $totalRevenue / $activeCount : 0
            ];
        });
    }

    private function getBookingChart()
    {
        $months = collect(range(11, 0))->map(function($i) {
            $date = now()->startOfMonth()->subMonths($i);
            return [
                'label' => $date->format('M Y'),
                'month' => $date->month,
                'year' => $date->year
            ];
        });

        $bookings = Rental::selectRaw('MONTH(created_at) as month, YEAR(created_at) as year, COUNT(*) as total')
            ->whereYear('created_at', '>=', now()->subMonths(11)->year)
            ->groupBy('year', 'month')
            ->get();

        $data = $months->map(function($month) use ($bookings) {
            $monthData = $bookings->first(function($booking) use ($month) {
                return $booking->month == $month['month'] && $booking->year == $month['year'];
            });

            return [
                'label' => $month['label'],
                'total' => $monthData ? $monthData->total : 0
            ];
        });

        return [
            'labels' => $data->pluck('label'),
            'data' => $data->pluck('total')
        ];
    }
}