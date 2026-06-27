<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Pengumpulan;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $mapel   = MataPelajaran::orderBy('nama_pelajaran')->get();
        $rekap   = collect();
        $filter  = $request->only(['semester', 'tahun_ajaran', 'mata_pelajaran_id']);

        if ($request->filled('semester') && $request->filled('tahun_ajaran')) {
            $rekap = Pengumpulan::with(['siswa.kelas', 'tugas.mataPelajaran'])
                ->where('status', 'sudah_dinilai')
                ->whereHas('tugas', function ($q) use ($request) {
                    $q->where('semester', $request->semester)
                      ->where('tahun_ajaran', $request->tahun_ajaran)
                      ->when($request->mata_pelajaran_id, fn($q) =>
                          $q->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                      );
                })
                ->get()
                ->groupBy('siswa_id');
        }

        return view('admin.rekap-nilai.index', compact('mapel', 'rekap', 'filter'));
    }
}
