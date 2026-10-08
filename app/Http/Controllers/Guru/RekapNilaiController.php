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
        $periodes   = Periode::orderBy('id', 'desc')->get();
        $periodeId  = $request->periode_id ?? Periode::where('is_active', true)->value('id');
        $kelasGuru  = auth()->user()->mengajarKelas;
        
        if ($kelasGuru->isEmpty()) {
            return view('guru.rekap-nilai.index', [
                'periodes' => $periodes, 'periodeId' => $periodeId, 'kelasGuru' => $kelasGuru,
                'kelasId' => null, 'mataPelajaranId' => null, 'mapel' => collect(), 
                'siswaList' => collect()
            ]);
        }

        $kelasId = $request->kelas_id ?? $kelasGuru->first()->id;

        // Filter Mapel berdasarkan Jadwal Mengajar di kelas dan periode tersebut
        $mapel = auth()->user()->jadwalMengajar()
            ->where('kelas_id', $kelasId)
            ->where('periode_id', $periodeId)
            ->with('mataPelajaran')
            ->get()
            ->pluck('mataPelajaran')
            ->unique('id');

        $mataPelajaranId = $request->mata_pelajaran_id ?? ($mapel->first()->id ?? null);

        // Pastikan param URL ada untuk bookmark konsisten
        if (!$request->has('kelas_id') || !$request->has('mata_pelajaran_id')) {
            return redirect()->route('guru.rekap-nilai.index', array_merge($request->all(), [
                'kelas_id' => $kelasId,
                'mata_pelajaran_id' => $mataPelajaranId
            ]));
        }

        $querySiswa = \App\Models\User::role('siswa')
            ->whereHas('rombel', function($q) use ($kelasId, $periodeId) {
                $q->where('kelas_id', $kelasId)
                  ->where('periode_id', $periodeId);
            });

        if ($request->filled('search')) {
            $search = $request->search;
            $querySiswa->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%");
            });
        }
            
        $siswaList = $querySiswa->orderBy('nama_lengkap')->paginate(15)->withQueryString();

        // Ambil semua pengumpulan siswa target untuk mapel ini
        $pengumpulan = Pengumpulan::with(['tugas' => function($q) {
                $q->select('id', 'judul', 'deadline');
            }])
            ->whereIn('siswa_id', $siswaList->pluck('id'))
            ->whereHas('tugas', function ($q) use ($periodeId, $mataPelajaranId) {
                $q->where('guru_id', auth()->id())
                  ->where('periode_id', $periodeId)
                  ->where('mata_pelajaran_id', $mataPelajaranId);
            })
            ->get()
            ->groupBy('siswa_id');

        return view('guru.rekap-nilai.index', compact('periodes', 'periodeId', 'kelasGuru', 'kelasId', 'mapel', 'mataPelajaranId', 'siswaList', 'pengumpulan'));
    }

    public function detail(Request $request)
    {
        $request->validate([
            'siswa_id' => 'required|exists:users,id',
            'kelas_id' => 'required|exists:kelas,id',
            'mata_pelajaran_id' => 'required|exists:mata_pelajaran,id',
            'periode_id' => 'required|exists:periodes,id',
        ]);

        $siswa = \App\Models\User::findOrFail($request->siswa_id);
        $mapel = MataPelajaran::findOrFail($request->mata_pelajaran_id);

        $semuaTugas = \App\Models\Tugas::where('guru_id', auth()->id())
            ->where('periode_id', $request->periode_id)
            ->where('mata_pelajaran_id', $request->mata_pelajaran_id)
            ->whereHas('kelas', fn($q) => $q->where('kelas.id', $request->kelas_id))
            ->orderBy('deadline', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $pengumpulan = Pengumpulan::where('siswa_id', $siswa->id)
            ->whereIn('tugas_id', $semuaTugas->pluck('id'))
            ->get()
            ->keyBy('tugas_id');

        return view('guru.rekap-nilai.detail', compact('siswa', 'mapel', 'semuaTugas', 'pengumpulan', 'request'));
    }
}
