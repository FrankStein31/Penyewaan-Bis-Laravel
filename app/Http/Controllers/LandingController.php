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
        $buses = Bus::select('buses.*')
            ->withCount('rentals')
            ->where('status', 'tersedia')
            ->where('is_active', 1)
            ->orderBy('rentals_count', 'desc')
            ->take(3)
            ->get()
            ->groupBy('type');

        return view('landing', compact('buses'));
    }
}
