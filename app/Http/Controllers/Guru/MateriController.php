<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Periode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MateriController extends Controller
{
    public function index(Request $request)
    {
        $periodeId = Periode::where('is_active', true)->value('id');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $query = Materi::with('mataPelajaran')
            ->where('guru_id', $user->id)
            ->where('periode_id', $periodeId);
            
        if ($request->filled('mapel_id')) {
            $query->where('mata_pelajaran_id', $request->mapel_id);
        }
            
        $materi = $query->latest()->get();
        
        // List mapel untuk filter
        $mapels = $user->jadwalMengajar()
            ->where('periode_id', $periodeId)
            ->with('mataPelajaran')
            ->get()
            ->pluck('mataPelajaran')
            ->unique('id');

        return view('guru.materi.index', compact('materi', 'mapels'));
    }

    public function create()
    {
        $periodeId = Periode::where('is_active', true)->value('id');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $jadwal = $user->jadwalMengajar()
            ->where('periode_id', $periodeId)
            ->with(['mataPelajaran', 'kelas'])
            ->get();
            
        $mapel = $jadwal->pluck('mataPelajaran')->unique('id');
        $kelas = $jadwal->pluck('kelas')->unique('id');

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

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $data['guru_id'] = $user->id;
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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $periodeId = Periode::where('is_active', true)->value('id');
        $materi = Materi::with('kelas')->where('guru_id', $user->id)->findOrFail($id);
        
        $jadwal = $user->jadwalMengajar()
            ->where('periode_id', $periodeId)
            ->with(['mataPelajaran', 'kelas'])
            ->get();
            
        $mapel = $jadwal->pluck('mataPelajaran')->unique('id');
        $kelas = $jadwal->pluck('kelas')->unique('id');
        
        return view('guru.materi.edit', compact('materi', 'mapel', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $materi = Materi::where('guru_id', $user->id)->findOrFail($id);

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
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $materi = Materi::where('guru_id', $user->id)->findOrFail($id);
        $materi->delete();

        return redirect()->route('guru.materi.index')->with('success', 'Materi berhasil dihapus.');
    }
}
