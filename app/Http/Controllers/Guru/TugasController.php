<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Tugas;
use App\Models\Periode;
use App\Models\Kelas;
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
        $kelas = auth()->user()->mengajarKelas()->orderBy('nama_kelas')->get();
        return view('guru.tugas.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_tugas'        => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
            'deadline'          => 'nullable|date',
            'kelas_id'          => 'required|array|min:1',
            'kelas_id.*'        => 'exists:kelas,id',
        ]);

        $data['guru_id'] = auth()->id();
        $data['periode_id'] = Periode::where('is_active', true)->value('id');

        if ($request->hasFile('file_tugas')) {
            $data['file_tugas'] = $request->file('file_tugas')->store('uploads/tugas', 'public');
        }

        $tugas = Tugas::create($data);
        $tugas->kelas()->sync($request->kelas_id);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('guru.tugas.edit', $id);
    }

    public function edit($id)
    {
        $tugas = Tugas::with('kelas')->where('guru_id', auth()->id())->findOrFail($id);
        $mapel = MataPelajaran::orderBy('nama_pelajaran')->get();
        $kelas = auth()->user()->mengajarKelas()->orderBy('nama_kelas')->get();
        return view('guru.tugas.edit', compact('tugas', 'mapel', 'kelas'));
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
            'kelas_id'          => 'required|array|min:1',
            'kelas_id.*'        => 'exists:kelas,id',
        ]);

        if ($request->hasFile('file_tugas')) {
            if ($tugas->file_tugas) {
                Storage::disk('public')->delete($tugas->file_tugas);
            }
            $data['file_tugas'] = $request->file('file_tugas')->store('uploads/tugas', 'public');
        }

        $tugas->update($data);
        $tugas->kelas()->sync($request->kelas_id);

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $tugas = Tugas::where('guru_id', auth()->id())->findOrFail($id);
        $tugas->delete();

        return redirect()->route('guru.tugas.index')->with('success', 'Tugas berhasil dihapus.');
    }
}
