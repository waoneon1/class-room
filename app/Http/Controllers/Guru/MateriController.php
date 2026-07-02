<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\MataPelajaran;
use App\Models\Periode;
use App\Models\Kelas;
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
        $kelas = auth()->user()->mengajarKelas()->orderBy('nama_kelas')->get();
        return view('guru.materi.create', compact('mapel', 'kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_materi'       => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
            'kelas_id'          => 'required|array|min:1',
            'kelas_id.*'        => 'exists:kelas,id',
        ]);

        $data['guru_id'] = auth()->id();
        $data['periode_id'] = Periode::where('is_active', true)->value('id');

        if ($request->hasFile('file_materi')) {
            $data['file_materi'] = $request->file('file_materi')->store('uploads/materi', 'public');
        }

        $materi = Materi::create($data);
        $materi->kelas()->sync($request->kelas_id);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil ditambahkan.');
    }

    public function show($id)
    {
        return redirect()->route('guru.materi.edit', $id);
    }

    public function edit($id)
    {
        $materi = Materi::with('kelas')->where('guru_id', auth()->id())->findOrFail($id);
        $mapel  = MataPelajaran::orderBy('nama_pelajaran')->get();
        $kelas = auth()->user()->mengajarKelas()->orderBy('nama_kelas')->get();
        return view('guru.materi.edit', compact('materi', 'mapel', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $materi = Materi::where('guru_id', auth()->id())->findOrFail($id);

        $data = $request->validate([
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'judul'             => 'required|string|max:255',
            'deskripsi'         => 'nullable|string',
            'file_materi'       => 'nullable|file|mimes:pdf,doc,docx,xlsx,xls,jpg,jpeg,png|max:10240',
            'kelas_id'          => 'required|array|min:1',
            'kelas_id.*'        => 'exists:kelas,id',
        ]);

        if ($request->hasFile('file_materi')) {
            if ($materi->file_materi) {
                Storage::disk('public')->delete($materi->file_materi);
            }
            $data['file_materi'] = $request->file('file_materi')->store('uploads/materi', 'public');
        }

        $materi->update($data);
        $materi->kelas()->sync($request->kelas_id);

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $materi = Materi::where('guru_id', auth()->id())->findOrFail($id);
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
