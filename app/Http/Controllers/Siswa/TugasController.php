<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;
use App\Models\PengumpulanFile;
use App\Models\Tugas;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    public function index()
    {
        $tugasList = Tugas::with(['guru', 'mataPelajaran'])->latest()->get();
        $siswaId   = auth()->id();

        // Map status pengumpulan per tugas untuk siswa ini
        $statusMap = Pengumpulan::where('siswa_id', $siswaId)
            ->pluck('status', 'tugas_id');

        return view('siswa.tugas.index', compact('tugasList', 'statusMap'));
    }

    public function show($id)
    {
        $tugas       = Tugas::with(['guru', 'mataPelajaran'])->findOrFail($id);
        $pengumpulan = Pengumpulan::where('tugas_id', $id)
            ->where('siswa_id', auth()->id())
            ->with('files')
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function kumpul(Request $request, $id)
    {
        $tugas = Tugas::findOrFail($id);

        // Cek apakah sudah pernah kumpul
        $existing = Pengumpulan::where('tugas_id', $id)
            ->where('siswa_id', auth()->id())
            ->first();

        if ($existing) {
            return redirect()->route('siswa.tugas.show', $id)
                ->with('error', 'Kamu sudah pernah mengumpulkan tugas ini.');
        }

        $request->validate([
            'foto'   => 'required|array|min:1|max:10',
            'foto.*' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $terlambat = $tugas->deadline && now()->gt($tugas->deadline);

        $pengumpulan = Pengumpulan::create([
            'tugas_id'  => $tugas->id,
            'siswa_id'  => auth()->id(),
            'status'    => 'sudah_kumpul',
            'terlambat' => $terlambat,
        ]);

        foreach ($request->file('foto') as $index => $file) {
            $path = $file->store('uploads/pengumpulan', 'public');
            PengumpulanFile::create([
                'pengumpulan_id' => $pengumpulan->id,
                'file_path'      => $path,
                'urutan'         => $index + 1,
            ]);
        }

        $msg = $terlambat
            ? 'Tugas berhasil dikumpulkan (terlambat dari deadline).'
            : 'Tugas berhasil dikumpulkan!';

        return redirect()->route('siswa.tugas.show', $id)->with('success', $msg);
    }
}
