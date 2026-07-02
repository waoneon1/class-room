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

        $query = Pengumpulan::with(['siswa', 'tugas.mataPelajaran'])
            ->where('status', 'sudah_dinilai')
            ->whereHas('tugas', function ($q) use ($periodeId, $request) {
                $q->where('guru_id', auth()->id())
                  ->where('periode_id', $periodeId);
                if ($request->mata_pelajaran_id) {
                    $q->where('mata_pelajaran_id', $request->mata_pelajaran_id);
                }
            });

        $rekap = $query->get()->groupBy('siswa_id');

        return view('guru.rekap-nilai.index', compact('mapel', 'periodes', 'rekap', 'periodeId'));
    }
}
