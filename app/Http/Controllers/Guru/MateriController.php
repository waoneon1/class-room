<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MataPelajaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index()
    {
        $materi = Materi::with('mataPelajaran')
            ->where('guru_id', auth()->id())
            ->latest()
            ->get();
        return view('guru.materi.index', compact('materi'));
    }

    public function create()
    {
        $mapel = MataPelajaran::orderBy('nama_pelajaran')->get();
        return view('guru.materi.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_materi'       => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
        ]);

        $data['guru_id'] = auth()->id();

        if ($request->hasFile('file_materi')) {
            $data['file_materi'] = $request->file('file_materi')->store('uploads/materi', 'public');
        }

        Materi::create($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('guru.materi.edit', $id);
    }

    public function edit($id)
    {
        $materi = Materi::where('guru_id', auth()->id())->findOrFail($id);
        $mapel  = MataPelajaran::orderBy('nama_pelajaran')->get();
        return view('guru.materi.edit', compact('materi', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::where('guru_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_materi'       => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
        ]);

        if ($request->hasFile('file_materi')) {
            if ($materi->file_materi) {
                Storage::disk('public')->delete($materi->file_materi);
            }
            $data['file_materi'] = $request->file('file_materi')->store('uploads/materi', 'public');
        }

        $materi->update($data);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = Materi::where('guru_id', auth()->id())->findOrFail($id);
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
