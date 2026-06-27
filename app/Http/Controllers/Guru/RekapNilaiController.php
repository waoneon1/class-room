<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $mapel        = MataPelajaran::orderBy('nama_pelajaran')->get();
        $tahunAjaran  = $request->tahun_ajaran ?? date('Y') . '/' . (date('Y') + 1);
        $semester     = $request->semester ?? 1;

        $query = Pengumpulan::with(['siswa', 'tugas.mataPelajaran'])
            ->where('status', 'sudah_dinilai')
            ->whereHas('tugas', function ($q) use ($semester, $tahunAjaran, $request) {
                $q->where('guru_id', auth()->id())
                  ->where('semester', $semester)
                  ->where('tahun_ajaran', $tahunAjaran);
                if ($request->mata_pelajaran_id) {
                    $q->where('mata_pelajaran_id', $request->mata_pelajaran_id);
                }
            });

        $rekap = $query->get()->groupBy('siswa_id');

        return view('guru.rekap-nilai.index', compact('mapel', 'rekap', 'semester', 'tahunAjaran'));
    }
}
