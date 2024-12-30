<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Rating;
use App\Models\Testimonial;

class HomeController extends Controller
{
    public function index()
    {
        // Jika user sudah login, redirect ke dashboard
        if (auth()->check()) {
            return redirect()->route(auth()->user()->role . '.dashboard');
        }

        // Jika belum login, tampilkan landing page
        $data = [
            'buses' => Bus::withAvg('ratings', 'rating')
                         ->where('status', 'available')
                         ->take(6)
                         ->get(),
            'testimonials' => Rating::with('user')
                                  ->where('comment', '!=', null)
                                  ->latest()
                                  ->take(3)
                                  ->get()
        ];

        return view('landing', $data);
    }

    public function ownerDashboard()
    {
        if (auth()->user()->role !== 'owner') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        return view('owner.dashboard');
    }

    public function adminDashboard()
    {
        if (auth()->user()->role !== 'admin') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        return view('admin.dashboard');
    }

    public function customerDashboard()
    {
        if (auth()->user()->role !== 'customer') {
            return redirect('/')->with('error', 'Unauthorized access');
        }
        return view('customer.dashboard');
    }
}
