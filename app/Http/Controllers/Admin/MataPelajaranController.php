<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    public function index()
    {
        $mapel = MataPelajaran::withCount(['materi', 'tugas'])->orderBy('nama_pelajaran')->get();
        return view('admin.mata-pelajaran.index', compact('mapel'));
    }

    public function create()
    {
        return view('admin.mata-pelajaran.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_pelajaran' => 'required|string|max:100|unique:mata_pelajaran,nama_pelajaran',
        ]);

        MataPelajaran::create($request->only('nama_pelajaran'));

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('admin.mata-pelajaran.edit', $id);
    }

    public function edit($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        return view('admin.mata-pelajaran.edit', compact('mapel'));
    }

    public function update(Request $request, $id)
    {
        $mapel = MataPelajaran::findOrFail($id);

        $request->validate([
            'nama_pelajaran' => "required|string|max:100|unique:mata_pelajaran,nama_pelajaran,{$id}",
        ]);

        $mapel->update($request->only('nama_pelajaran'));

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $mapel = MataPelajaran::findOrFail($id);
        $mapel->delete();

        return redirect()->route('admin.mata-pelajaran.index')->with('success', 'Mata pelajaran berhasil dihapus.');
    }
}
