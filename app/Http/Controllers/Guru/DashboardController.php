<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;

class DashboardController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $guru        = auth()->user();
        $totalMateri = $guru->materi()->count();
        $totalTugas  = $guru->tugas()->count();
        $belumDinilai = Pengumpulan::where('status', 'sudah_kumpul')
            ->whereHas('tugas', fn($q) => $q->where('guru_id', $guru->id))
            ->count();

        // Data for Chart Filters
        $mapelList = \App\Models\MataPelajaran::orderBy('nama_pelajaran')->get();
        $mapelId   = $request->mata_pelajaran_id;

        $tugasQuery = \App\Models\Tugas::where('guru_id', $guru->id)->orderBy('created_at', 'desc');
        if ($mapelId) {
            $tugasQuery->where('mata_pelajaran_id', $mapelId);
        }
        $tugasList = $tugasQuery->get();

        $tugasId = $request->tugas_id;
        if (!$tugasId && $tugasList->isNotEmpty()) {
            $tugasId = $tugasList->first()->id;
        }

        $chartLabels = [];
        $chartData   = [];
        $selectedTugasName = '';

        if ($tugasId) {
            $tugas = \App\Models\Tugas::find($tugasId);
            if ($tugas) {
                $selectedTugasName = $tugas->judul;
                $pengumpulan = Pengumpulan::with('siswa')
                    ->where('tugas_id', $tugasId)
                    ->where('status', 'sudah_dinilai')
                    ->get();
                
                foreach ($pengumpulan as $p) {
                    $chartLabels[] = $p->siswa->nama_lengkap;
                    $chartData[]   = $p->nilai;
                }
            }
        }

        return view('guru.dashboard', compact(
            'totalMateri', 'totalTugas', 'belumDinilai', 
            'mapelList', 'mapelId', 'tugasList', 'tugasId',
            'chartLabels', 'chartData', 'selectedTugasName'
        ));
    }
}
