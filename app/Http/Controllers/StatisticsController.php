<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Driver;
use App\Models\Rental;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function daily()
    {
        $data = [
            'rentals' => Rental::whereDate('created_at', today())
                              ->count(),
            'income' => Payment::whereDate('created_at', today())
                             ->where('status', 'verified')
                             ->sum('amount'),
            'hourlyStats' => $this->getHourlyStats(),
        ];

        return view('owner.statistics.daily', $data);
    }

    public function monthly()
    {
        $data = [
            'rentals' => Rental::whereMonth('created_at', now()->month)
                              ->count(),
            'income' => Payment::whereMonth('created_at', now()->month)
                             ->where('status', 'verified')
                             ->sum('amount'),
            'dailyStats' => $this->getDailyStats(),
        ];

        return view('owner.statistics.monthly', $data);
    }

    public function yearly()
    {
        $data = [
            'rentals' => Rental::whereYear('created_at', now()->year)
                              ->count(),
            'income' => Payment::whereYear('created_at', now()->year)
                             ->where('status', 'verified')
                             ->sum('amount'),
            'monthlyStats' => $this->getMonthlyStats(),
        ];

        return view('owner.statistics.yearly', $data);
    }

    public function busUsage()
    {
        $data = [
            'busStats' => Bus::withCount('rentals')
                           ->withAvg('ratings', 'rating')
                           ->get(),
            'usageByType' => $this->getBusUsageByType(),
        ];

        return view('owner.statistics.bus', $data);
    }

    public function driverHours()
    {
        $data = [
            'drivers' => Driver::withCount('rentals')
                             ->withAvg('ratings', 'rating')
                             ->orderBy('rentals_count', 'desc')
                             ->get(),
        ];

        return view('owner.statistics.driver', $data);
    }

    public function fleetRanking()
    {
        $data = [
            'topBuses' => Bus::withCount('rentals')
                           ->withAvg('ratings', 'rating')
                           ->orderBy('ratings_avg_rating', 'desc')
                           ->take(10)
                           ->get(),
        ];

        return view('owner.statistics.fleet', $data);
    }

    private function getHourlyStats()
    {
        return Rental::whereDate('created_at', today())
                    ->select(DB::raw('HOUR(created_at) as hour'), DB::raw('count(*) as total'))
                    ->groupBy('hour')
                    ->orderBy('hour')
                    ->get();
    }

    private function getDailyStats()
    {
        return Rental::whereMonth('created_at', now()->month)
                    ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
                    ->groupBy('date')
                    ->orderBy('date')
                    ->get();
    }

    private function getMonthlyStats()
    {
        return Rental::whereYear('created_at', now()->year)
                    ->select(DB::raw('MONTH(created_at) as month'), DB::raw('count(*) as total'))
                    ->groupBy('month')
                    ->orderBy('month')
                    ->get();
    }

    private function getBusUsageByType()
    {
        return Bus::select('type', DB::raw('count(*) as total'))
                 ->groupBy('type')
                 ->get();
    }
} 