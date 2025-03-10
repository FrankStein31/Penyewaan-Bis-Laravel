<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LandingController extends Controller
{
    public function index()
    {
        // Get top 3 most rented buses
        $topBuses = Bus::select('buses.*', DB::raw('COUNT(rentals.id) as rental_count'))
            ->leftJoin('rentals', 'buses.id', '=', 'rentals.bus_id')
            ->where('rentals.status', 'selesai')
            ->groupBy('buses.id')
            ->orderBy('rental_count', 'desc')
            ->limit(3)
            ->get();

        return view('landing', compact('topBuses'));
    }
}