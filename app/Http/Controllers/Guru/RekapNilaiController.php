<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Pengumpulan;
use App\Models\Periode;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $mapel      = MataPelajaran::orderBy('nama_pelajaran')->get();
        $periodes   = Periode::orderBy('id', 'desc')->get();
        $periodeId  = $request->periode_id ?? Periode::where('is_active', true)->value('id');
        $kelasGuru  = auth()->user()->mengajarKelas;
        $kelasId    = $request->kelas_id;

        $querySiswa = \App\Models\User::role('siswa')->whereIn('kelas_id', $kelasGuru->pluck('id'));
        if ($kelasId) {
            $querySiswa->where('kelas_id', $kelasId);
        }
        $siswaList = $querySiswa->orderBy('nama_lengkap')->get();

        $pengumpulan = Pengumpulan::with(['tugas.mataPelajaran'])
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->whereHas('tugas', function ($q) use ($periodeId, $request) {
                $q->where('guru_id', auth()->id())
                  ->where('periode_id', $periodeId);
                if ($request->mata_pelajaran_id) {
                    $q->where('mata_pelajaran_id', $request->mata_pelajaran_id);
                }
            })
            ->get()
            ->groupBy('siswa_id');

        return view('guru.rekap-nilai.index', compact('mapel', 'periodes', 'periodeId', 'kelasGuru', 'kelasId', 'siswaList', 'pengumpulan'));
    }
}
