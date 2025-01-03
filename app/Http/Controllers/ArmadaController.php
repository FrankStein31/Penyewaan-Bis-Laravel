<?php

namespace App\Http\Controllers;

use App\Models\Armada;
use Illuminate\Http\Request;

class ArmadaController extends Controller
{
    public function index()
    {
        $armadas = Armada::all();
        return view('admin.armada.index', compact('armadas'));
    }

    public function create()
    {
        return view('admin.armada.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_armada' => 'required|string|max:255'
        ]);

        Armada::create($request->all());

        return redirect()->route('admin.armada.index')
            ->with('success', 'Armada berhasil ditambahkan');
    }

    public function edit(Armada $armada)
    {
        return view('admin.armada.edit', compact('armada'));
    }

    public function update(Request $request, Armada $armada)
    {
        $request->validate([
            'nama_armada' => 'required|string|max:255'
        ]);

        $armada->update($request->all());

        return redirect()->route('admin.armada.index')
            ->with('success', 'Armada berhasil diperbarui');
    }

    public function destroy(Armada $armada)
    {
        if($armada->buses()->count() > 0) {
            return back()->with('error', 'Tidak dapat menghapus armada yang memiliki bus');
        }

        $armada->delete();

        return redirect()->route('admin.armada.index')
            ->with('success', 'Armada berhasil dihapus');
    }
} 