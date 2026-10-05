<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use App\Models\Periode;
use App\Models\JadwalMengajar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JadwalMengajarController extends Controller
{
    public function index()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        $periodeAktif = Periode::where('is_active', true)->first();
        return view('admin.jadwal_mengajar.index', compact('kelas', 'periodeAktif'));
    }

    public function show($kelas_id)
    {
        $kelas = Kelas::findOrFail($kelas_id);
        $periodeAktif = Periode::where('is_active', true)->first();
        $mapels = MataPelajaran::orderBy('nama_pelajaran')->get();
        
        $gurus = User::where('role', 'guru')
            ->whereHas('mengajarKelas', function($q) use ($kelas_id) {
                $q->where('kelas_id', $kelas_id);
            })
            ->orderBy('nama_lengkap')
            ->get();
        
        $jadwal = JadwalMengajar::where('kelas_id', $kelas_id)
            ->where('periode_id', $periodeAktif->id ?? 0)
            ->get()
            ->keyBy('mata_pelajaran_id');

        return view('admin.jadwal_mengajar.show', compact('kelas', 'periodeAktif', 'mapels', 'gurus', 'jadwal'));
    }

    public function store(Request $request)
    {
        $kelas_id = $request->kelas_id;
        $periodeAktif = Periode::where('is_active', true)->first();
        
        if (!$periodeAktif) {
            return back()->with('error', 'Tidak ada periode aktif.');
        }
        
        DB::beginTransaction();
        try {
            JadwalMengajar::where('kelas_id', $kelas_id)
                ->where('periode_id', $periodeAktif->id)
                ->delete();

            if ($request->has('guru')) {
                foreach ($request->guru as $mapel_id => $guru_id) {
                    if ($guru_id) {
                        JadwalMengajar::create([
                            'user_id' => $guru_id,
                            'kelas_id' => $kelas_id,
                            'mata_pelajaran_id' => $mapel_id,
                            'periode_id' => $periodeAktif->id,
                        ]);
                    }
                }
            }
            DB::commit();
            return redirect()->route('admin.jadwal-mengajar.show', $kelas_id)->with('success', 'Jadwal mengajar berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memperbarui jadwal: ' . $e->getMessage());
        }
    }
}
