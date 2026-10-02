<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;
use App\Models\PengumpulanFile;
use App\Models\Tugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TugasController extends Controller
{
    public function index()
    {
        if (!request()->has('subject_id')) {
            return redirect()->route('siswa.dashboard')->with('error', 'Silakan pilih mata pelajaran terlebih dahulu.');
        }

        /** @var \App\Models\User $user */
        $user = auth()->user();
        $siswaId   = $user->id;
        $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
        
        $rombel = $user->rombel()->wherePivot('periode_id', $periodeId)->first();
        $kelasId = $rombel ? $rombel->id : null;
        
        $subjectId = request('subject_id');

        $tugasList = Tugas::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $subjectId)
            ->where('periode_id', $periodeId)
            ->whereHas('kelas', function($q) use ($kelasId) {
                $q->where('kelas.id', $kelasId);
            })
            ->latest()
            ->get();

        // Map status pengumpulan per tugas untuk siswa ini
        $statusMap = Pengumpulan::where('siswa_id', $siswaId)
            ->whereIn('tugas_id', $tugasList->pluck('id'))
            ->pluck('status', 'tugas_id');

        return view('siswa.tugas.index', compact('tugasList', 'statusMap'));
    }

    public function show($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $tugas       = Tugas::with(['guru', 'mataPelajaran'])->findOrFail($id);
        $pengumpulan = Pengumpulan::where('tugas_id', $id)
            ->where('siswa_id', $user->id)
            ->with('files')
            ->first();

        return view('siswa.tugas.show', compact('tugas', 'pengumpulan'));
    }

    public function kumpul(Request $request, $id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        $tugas = Tugas::findOrFail($id);

        // Cek apakah sudah pernah kumpul
        $existing = Pengumpulan::where('tugas_id', $id)
            ->where('siswa_id', $user->id)
            ->first();

        if ($existing) {
            return redirect()->route('siswa.tugas.show', $id)
                ->with('error', 'Kamu sudah pernah mengumpulkan tugas ini.');
        }

        $request->validate([
            'foto'   => 'required|array|min:1|max:10',
            'foto.*' => 'required|image|mimes:jpg,jpeg,png|max:5120',
        ]);

        $terlambat = $tugas->deadline && now()->gt($tugas->deadline);

        $pengumpulan = Pengumpulan::create([
            'tugas_id'  => $tugas->id,
            'siswa_id'  => $user->id,
            'status'    => 'sudah_kumpul',
            'terlambat' => $terlambat,
        ]);

        foreach ($request->file('foto') as $index => $file) {
            $path = $file->store('uploads/pengumpulan', 'public');
            PengumpulanFile::create([
                'pengumpulan_id' => $pengumpulan->id,
                'file_path'      => $path,
                'urutan'         => $index + 1,
            ]);
        }

        $msg = $terlambat
            ? 'Tugas berhasil dikumpulkan (terlambat dari deadline).'
            : 'Tugas berhasil dikumpulkan!';

        return redirect()->route('siswa.tugas.show', $id)->with('success', $msg);
    }

    public function batal($id)
    {
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $pengumpulan = Pengumpulan::where('tugas_id', $id)
            ->where('siswa_id', $user->id)
            ->firstOrFail();

        if ($pengumpulan->status === 'sudah_dinilai') {
            return redirect()->route('siswa.tugas.show', $id)
                ->with('error', 'Tugas yang sudah dinilai tidak dapat dibatalkan.');
        }

        foreach ($pengumpulan->files as $file) {
            if ($file->file_path) {
                Storage::disk('public')->delete($file->file_path);
            }
            $file->delete();
        }

        $pengumpulan->delete();

        return redirect()->route('siswa.tugas.show', $id)
            ->with('success', 'Pengumpulan tugas berhasil dibatalkan.');
    }
}
