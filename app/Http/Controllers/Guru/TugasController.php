<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        $tugas = Tugas::with('mataPelajaran')
            ->where('guru_id', auth()->id())
            ->latest()
            ->get();
        return view('guru.tugas.index', compact('tugas'));
    }

    public function create()
    {
        $mapel = MataPelajaran::orderBy('nama_pelajaran')->get();
        return view('guru.tugas.create', compact('mapel'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_tugas'        => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
            'deadline'          => 'nullable|date',
            'semester'          => 'required|in:1,2',
            'tahun_ajaran'      => 'required|string|max:20',
        ]);

        $data['guru_id'] = auth()->id();

        if ($request->hasFile('file_tugas')) {
            $data['file_tugas'] = $request->file('file_tugas')->store('uploads/tugas', 'public');
        }

        Tugas::create($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('guru.tugas.edit', $id);
    }

    public function edit($id)
    {
        $tugas = Tugas::where('guru_id', auth()->id())->findOrFail($id);
        $mapel = MataPelajaran::orderBy('nama_pelajaran')->get();
        return view('guru.tugas.edit', compact('tugas', 'mapel'));
    }

    public function update(Request $request, $id)
    {
        $tugas = Tugas::where('guru_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_tugas'        => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
            'deadline'          => 'nullable|date',
            'semester'          => 'required|in:1,2',
            'tahun_ajaran'      => 'required|string|max:20',
        ]);

        if ($request->hasFile('file_tugas')) {
            if ($tugas->file_tugas) {
                Storage::disk('public')->delete($tugas->file_tugas);
            }
            $data['file_tugas'] = $request->file('file_tugas')->store('uploads/tugas', 'public');
        }

        $tugas->update($data);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas = Tugas::where('guru_id', auth()->id())->findOrFail($id);
        $tugas->delete();

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
