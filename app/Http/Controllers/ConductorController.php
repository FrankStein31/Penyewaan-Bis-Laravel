<?php

namespace App\Http\Controllers;

use App\Models\Conductor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConductorController extends Controller
{
    public function index()
    {
        $conductors = Conductor::all();
        return view('pages.conductors.index', compact('conductors'));
    }

    public function create()
    {
        return view('pages.conductors.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:conductors',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:available,on_duty,off',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->all();
            
            if ($request->hasFile('photo')) {
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/conductors'), $filename);
                $data['photo'] = $filename;
            }

            $data['is_active'] = $request->has('is_active');
            
            Conductor::create($data);

            return redirect()
                ->route('conductors.index')
                ->with('success', 'Data kernet berhasil ditambahkan.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit(Conductor $conductor)
    {
        return view('pages.conductors.edit', compact('conductor'));
    }

    public function update(Request $request, Conductor $conductor)
    {
        $request->validate([
            'nik' => 'required|string|max:255|unique:conductors,nik,' . $conductor->id,
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'address' => 'required|string',
            'status' => 'required|in:available,on_duty,off',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $data = $request->all();
            
            if ($request->hasFile('photo')) {
                // Hapus foto lama jika ada
                if ($conductor->photo) {
                    Storage::delete('public/conductors/' . $conductor->photo);
                }
                
                $photo = $request->file('photo');
                $filename = time() . '_' . $photo->getClientOriginalName();
                $photo->move(public_path('storage/conductors'), $filename);
                $data['photo'] = $filename;
            }

            $data['is_active'] = $request->has('is_active');
            
            $conductor->update($data);

            return redirect()
                ->route('conductors.index')
                ->with('success', 'Data kernet berhasil diperbarui.');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy(Conductor $conductor)
    {
        try {
            if ($conductor->photo) {
                Storage::delete('public/conductors/' . $conductor->photo);
            }
            
            $conductor->delete();

            return redirect()
                ->route('conductors.index')
                ->with('success', 'Data kernet berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
} 