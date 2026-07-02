<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Periode;
use Illuminate\Http\Request;

class PeriodeController extends Controller
{
    public function index()
    {
        $periodes = Periode::orderBy('id', 'desc')->get();
        return view('admin.periode.index', compact('periodes'));
    }

    public function create()
    {
        return view('admin.periode.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_periode' => 'required|string|max:100|unique:periodes,nama_periode',
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive) {
            Periode::where('is_active', true)->update(['is_active' => false]);
        }

        Periode::create([
            'nama_periode' => $request->nama_periode,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $periode = Periode::findOrFail($id);
        return view('admin.periode.edit', compact('periode'));
    }

    public function update(Request $request, $id)
    {
        $periode = Periode::findOrFail($id);

        $request->validate([
            'nama_periode' => "required|string|max:100|unique:periodes,nama_periode,{$id}",
            'is_active' => 'nullable|boolean',
        ]);

        $isActive = $request->has('is_active');

        if ($isActive && !$periode->is_active) {
            // Jika diaktifkan, matikan yang lain
            Periode::where('id', '!=', $id)->where('is_active', true)->update(['is_active' => false]);
        }

        $periode->update([
            'nama_periode' => $request->nama_periode,
            'is_active' => $isActive,
        ]);

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $periode = Periode::findOrFail($id);
        
        // Cek jika sedang aktif
        if ($periode->is_active) {
            return redirect()->route('admin.periode.index')->with('error', 'Tidak dapat menghapus periode yang sedang aktif.');
        }

        $periode->delete();

        return redirect()->route('admin.periode.index')->with('success', 'Periode berhasil dihapus.');
    }
}
