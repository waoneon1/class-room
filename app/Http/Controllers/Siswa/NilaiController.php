<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;

class NilaiController extends Controller
{
    public function index()
    {
        $siswaId = auth()->id();

        if (request()->has('subject_id')) {
            $subjectId = request('subject_id');
            $mataPelajaran = \App\Models\MataPelajaran::findOrFail($subjectId);

            $nilaiList = Pengumpulan::with(['tugas.mataPelajaran', 'tugas.guru'])
                ->where('siswa_id', $siswaId)
                ->where('status', 'sudah_dinilai')
                ->whereHas('tugas', function($q) use ($subjectId) {
                    $q->where('mata_pelajaran_id', $subjectId);
                })
                ->latest()
                ->get();

            return view('siswa.nilai.detail', compact('nilaiList', 'mataPelajaran'));
        }

        // Overall view
        $mataPelajaran = \App\Models\MataPelajaran::all();
        $pengumpulan = Pengumpulan::with('tugas')
            ->where('siswa_id', $siswaId)
            ->where('status', 'sudah_dinilai')
            ->get();

        $rekap = $mataPelajaran->map(function ($mp) use ($pengumpulan) {
            $tugasSubject = $pengumpulan->filter(function($p) use ($mp) {
                return $p->tugas->mata_pelajaran_id == $mp->id;
            });

            return [
                'mata_pelajaran' => $mp,
                'rata_rata' => $tugasSubject->count() > 0 ? $tugasSubject->avg('nilai') : null,
                'jumlah_tugas' => $tugasSubject->count()
            ];
        });

        return view('siswa.nilai.index', compact('rekap'));
    }
}
