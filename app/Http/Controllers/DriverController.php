<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DriverController extends Controller
{
    public function index()
    {
        $drivers = Driver::all();
        return view('pages.drivers.index', compact('drivers'));
    }

    public function create()
    {
        return view('pages.drivers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'license_expire' => 'required|date',
            'status' => 'required|in:available,on_duty,off',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->all();
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/drivers'), $filename);
                $data['photo'] = $filename;
            }

            $data['is_active'] = $request->has('is_active');
            
            Driver::create($data);

            return redirect()
                ->route('drivers.index')
                ->with('success', 'Data supir berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Driver $driver)
    {
        return view('pages.drivers.edit', compact('driver'));
    }

    public function update(Request $request, Driver $driver)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'license_number' => 'required|string|unique:drivers,license_number,' . $driver->id,
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'license_expire' => 'required|date',
            'status' => 'required|in:available,on_duty,off',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->all();
            
            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($driver->photo) {
                    Storage::delete('public/drivers/' . $driver->photo);
                }
                
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/drivers'), $filename);
                $data['photo'] = $filename;
            }

            // Handle is_active checkbox
            $data['is_active'] = $request->has('is_active');
            
            $driver->update($data);

            return redirect()
                ->route('drivers.index')
                ->with('success', 'Data supir berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Driver $driver)
    {
        if ($driver->photo) {
            Storage::delete('public/drivers/' . $driver->photo);
        }
        
        $driver->delete();

        return redirect()->route('drivers.index')
            ->with('success', 'Data supir berhasil dihapus.');
    }
} 