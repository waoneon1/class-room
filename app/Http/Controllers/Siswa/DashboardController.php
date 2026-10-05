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
        $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        // Ambil kelas aktif siswa di periode ini
        $rombel = $user->rombel()->wherePivot('periode_id', $periodeId)->first();
        $kelasId = $rombel ? $rombel->id : null;

        $gurusPerSubject = [];
        $mataPelajaran = collect();

        if ($kelasId) {
            // Ambil mapel dari jadwal mengajar di kelas dan periode tersebut
            $jadwals = \App\Models\JadwalMengajar::with(['mataPelajaran', 'guru'])
                ->where('kelas_id', $kelasId)
                ->where('periode_id', $periodeId)
                ->get();
                
            $mataPelajaran = $jadwals->pluck('mataPelajaran')->unique('id');
            
            foreach ($jadwals as $jadwal) {
                $gurusPerSubject[$jadwal->mata_pelajaran_id] = $jadwal->guru->nama_lengkap;
            }
        }

        return view('siswa.dashboard', compact('mataPelajaran', 'gurusPerSubject'));
    }

    public function subject($id)
    {
        $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $rombel = $user->rombel()->wherePivot('periode_id', $periodeId)->first();
        $kelasId = $rombel ? $rombel->id : null;
        
        $mataPelajaran = MataPelajaran::findOrFail($id);

        $materi = Materi::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $id)
            ->where('periode_id', $periodeId)
            ->whereHas('kelas', function ($q) use ($kelasId) {
                $q->where('kelas.id', $kelasId);
            })
            ->latest()
            ->get();

        $tugas = Tugas::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $id)
            ->where('periode_id', $periodeId)
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
