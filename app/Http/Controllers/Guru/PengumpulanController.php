<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;

class PengumpulanController extends Controller
{
    public function index($tugasId)
    {
        $tugas = Tugas::with('kelas')->where('guru_id', auth()->id())->findOrFail($tugasId);

        $pengumpulan = Pengumpulan::with('siswa')
            ->where('tugas_id', $tugas->id)
            ->get()
            ->keyBy('siswa_id');

        $totalSiswa = User::role('siswa')->whereIn('kelas_id', $tugas->kelas->pluck('id'))->count();
        $semuaSiswa = User::role('siswa')->whereIn('kelas_id', $tugas->kelas->pluck('id'))->orderBy('nama_lengkap')->get();

        return view('guru.pengumpulan.index', compact('tugas', 'pengumpulan', 'totalSiswa', 'semuaSiswa'));
    }

    public function show($pengumpulanId)
    {
        $pengumpulan = Pengumpulan::with(['siswa', 'tugas', 'files'])
            ->whereHas('tugas', fn($q) => $q->where('guru_id', auth()->id()))
            ->findOrFail($pengumpulanId);

        return view('guru.pengumpulan.show', compact('pengumpulan'));
    }

    public function nilai(Request $request, $pengumpulanId)
    {
        $pengumpulan = Pengumpulan::whereHas('tugas', fn($q) => $q->where('guru_id', auth()->id()))
            ->findOrFail($pengumpulanId);

        $request->validate([
            'nilai'   => 'required|integer|min:0|max:100',
            'catatan' => 'nullable|string|max:500',
        ]);

        $pengumpulan->update([
            'nilai'   => $request->nilai,
            'catatan' => $request->catatan,
            'status'  => 'sudah_dinilai',
        ]);

        return redirect()->route('guru.pengumpulan.index', $pengumpulan->tugas_id)
            ->with('success', 'Nilai berhasil disimpan.');
    }
}
