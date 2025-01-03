<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use App\Models\Armada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::with('armada')->get();
        return view('pages.buses.index', compact('buses'));
    }

    public function create()
    {
        $armadas = Armada::all();
        return view('admin.buses.create', compact('armadas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'armada_id' => 'required|exists:armada,armada_id',
            'plate_number' => 'required|unique:buses',
            'type' => 'required|in:long,short',
            'price_per_day' => 'required|numeric',
            'description' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $data = $request->all();
            
            // Set kapasitas berdasarkan tipe bus
            $data['capacity'] = $request->type === 'long' ? 63 : 33;
            
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('img/buses'), $filename);
                $data['image'] = $filename;
            }

            $data['is_active'] = $request->has('is_active');
            
            Bus::create($data);

            return redirect()
                ->route('buses.index')
                ->with('success', 'Data bus berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Bus $bus)
    {
        $armadas = Armada::all();
        return view('admin.buses.edit', compact('bus', 'armadas'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'armada_id' => 'required|exists:armada,armada_id',
            'plate_number' => 'required|string|max:255|unique:buses,plate_number,' . $bus->id,
            'type' => 'required|in:long,short',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:tersedia,disewa,maintenance'
        ]);

        try {
            $data = $request->all();
            
            // Set kapasitas berdasarkan tipe bus
            $data['capacity'] = $request->type === 'long' ? 63 : 33;
            
            if ($request->hasFile('image')) {
                if ($bus->image) {
                    $oldPath = public_path('img/buses/' . $bus->image);
                    if (file_exists($oldPath)) {
                        unlink($oldPath);
                    }
                }
                
                $image = $request->file('image');
                $filename = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('img/buses'), $filename);
                $data['image'] = $filename;
            }

            $data['is_active'] = $request->has('is_active');
            
            $bus->update($data);

            return redirect()
                ->route('buses.index')
                ->with('success', 'Data bus berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Bus $bus)
    {
        try {
            if ($bus->image) {
                $path = public_path('img/buses/' . $bus->image);
                if (file_exists($path)) {
                    unlink($path);
                }
            }
            
            $bus->delete();

            return redirect()
                ->route('buses.index')
                ->with('success', 'Data bus berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = Bus::query();
        $query->where('is_active', true);
        
        if (!$request->filled('status')) {
            $query->where('status', 'tersedia');
        }

        // Filter berdasarkan armada
        if ($request->filled('armada_id')) {
            $query->where('armada_id', $request->armada_id);
        }

        // Filter berdasarkan tipe bus
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter berdasarkan kapasitas
        if ($request->filled('capacity')) {
            $query->where('capacity', '>=', $request->capacity);
        }

        // Filter berdasarkan rentang harga
        if ($request->filled('price_min')) {
            $query->where('price_per_day', '>=', $request->price_min);
        }
        if ($request->filled('price_max')) {
            $query->where('price_per_day', '<=', $request->price_max);
        }

        // Search berdasarkan keyword
        if ($request->filled('keyword')) {
            $query->where(function($q) use ($request) {
                $q->where('plate_number', 'like', '%' . $request->keyword . '%')
                  ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        $buses = $query->with('armada')->get();
        $armadas = Armada::all();

        if ($request->ajax()) {
            return response()->json([
                'buses' => $buses
            ]);
        }

        return view('pages.buses.search', compact('buses', 'armadas'));
    }

    public function book(Bus $bus)
    {
        // Pastikan bus tersedia
        if ($bus->status !== 'tersedia') {
            return back()->with('error', 'Bus tidak tersedia untuk disewa');
        }

        return view('pages.buses.book', compact('bus'));
    }
} 