<?php

namespace App\Http\Controllers;

use App\Models\Bus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BusController extends Controller
{
    public function index()
    {
        $buses = Bus::all();
        return view('pages.buses.index', compact('buses'));
    }

    public function create()
    {
        return view('pages.buses.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255|unique:buses',
            'type' => 'required|in:umum,pariwisata,antarkota',
            'capacity' => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:tersedia,disewa,maintenance'
        ]);

        try {
            $data = $request->all();
            
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
        return view('pages.buses.edit', compact('bus'));
    }

    public function update(Request $request, Bus $bus)
    {
        $request->validate([
            'plate_number' => 'required|string|max:255|unique:buses,plate_number,' . $bus->id,
            'type' => 'required|in:umum,pariwisata,antarkota',
            'capacity' => 'required|integer|min:1',
            'price_per_day' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'required|in:tersedia,disewa,maintenance'
        ]);

        try {
            $data = $request->all();
            
            if ($request->hasFile('image')) {
                // Hapus foto lama jika ada
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
} 