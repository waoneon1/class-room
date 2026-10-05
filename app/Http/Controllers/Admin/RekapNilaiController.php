<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MataPelajaran;
use App\Models\Pengumpulan;
use App\Models\Periode;
use Illuminate\Http\Request;

class RekapNilaiController extends Controller
{
    public function index(Request $request)
    {
        $mapel    = MataPelajaran::orderBy('nama_pelajaran')->get();
        $periodes = Periode::orderBy('id', 'desc')->get();
        $rekap    = collect();
        $filter   = $request->only(['periode_id', 'mata_pelajaran_id']);

        if ($request->filled('periode_id')) {
            $rekap = Pengumpulan::with(['siswa.kelas', 'tugas.mataPelajaran'])
                ->where('status', 'sudah_dinilai')
                ->whereHas('tugas', function ($q) use ($request) {
                    $q->where('periode_id', $request->periode_id)
                      ->when($request->mata_pelajaran_id, fn($q) =>
                          $q->where('mata_pelajaran_id', $request->mata_pelajaran_id)
                      );
                })
                ->get()
                ->groupBy('siswa_id');
        }

        return view('admin.rekap-nilai.index', compact('mapel', 'periodes', 'rekap', 'filter'));
    }
}
