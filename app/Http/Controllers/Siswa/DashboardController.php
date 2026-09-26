<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Materi;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $mataPelajaran = MataPelajaran::all();
        $kelasId = auth()->user()->kelas_id;

        $gurusPerSubject = [];
        
        // From Materi
        $materiGurus = \Illuminate\Support\Facades\DB::table('materi')
            ->join('kelas_materi', 'materi.id', '=', 'kelas_materi.materi_id')
            ->join('users', 'materi.guru_id', '=', 'users.id')
            ->where('kelas_materi.kelas_id', $kelasId)
            ->select('materi.mata_pelajaran_id', 'users.nama_lengkap')
            ->distinct()
            ->get();

        foreach ($materiGurus as $mg) {
            $gurusPerSubject[$mg->mata_pelajaran_id] = $mg->nama_lengkap;
        }

        // From Tugas
        $tugasGurus = \Illuminate\Support\Facades\DB::table('tugas')
            ->join('kelas_tugas', 'tugas.id', '=', 'kelas_tugas.tugas_id')
            ->join('users', 'tugas.guru_id', '=', 'users.id')
            ->where('kelas_tugas.kelas_id', $kelasId)
            ->select('tugas.mata_pelajaran_id', 'users.nama_lengkap')
            ->distinct()
            ->get();

        foreach ($tugasGurus as $tg) {
            if (!isset($gurusPerSubject[$tg->mata_pelajaran_id])) {
                $gurusPerSubject[$tg->mata_pelajaran_id] = $tg->nama_lengkap;
            }
        }

        return view('siswa.dashboard', compact('mataPelajaran', 'gurusPerSubject'));
    }

    public function subject($id)
    {
        $kelasId = auth()->user()->kelas_id;
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $materi = Materi::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $id)
            ->whereHas('kelas', function ($q) use ($kelasId) {
                $q->where('kelas.id', $kelasId);
            })
            ->latest()
            ->get();

        $tugas = Tugas::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $id)
            ->whereHas('kelas', function ($q) use ($kelasId) {
                $q->where('kelas.id', $kelasId);
            })
            ->latest()
            ->get();

        $feed = $materi->map(fn($m) => ['type' => 'materi', 'obj' => $m, 'created_at' => $m->created_at])
            ->concat($tugas->map(fn($t) => ['type' => 'tugas', 'obj' => $t, 'created_at' => $t->created_at]))
            ->sortByDesc('created_at')
            ->values();

        return view('siswa.dashboard_subject', compact('feed', 'mataPelajaran'));
    }
}
