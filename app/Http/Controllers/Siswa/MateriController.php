<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        if (!request()->has('subject_id')) {
            return redirect()->route('siswa.dashboard')->with('error', 'Silakan pilih mata pelajaran terlebih dahulu.');
        }

        $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
        
        /** @var \App\Models\User $user */
        $user = auth()->user();
        
        $rombel = $user->rombel()->wherePivot('periode_id', $periodeId)->first();
        $kelasId = $rombel ? $rombel->id : null;
        
        $subjectId = request('subject_id');

        $materi = Materi::with(['guru', 'mataPelajaran'])
            ->where('mata_pelajaran_id', $subjectId)
            ->where('periode_id', $periodeId)
            ->whereHas('kelas', function($q) use ($kelasId) {
                $q->where('kelas.id', $kelasId);
            })
            ->latest()
            ->get();
        return view('siswa.materi.index', compact('materi'));
    }

    public function show($id)
    {
        $materi = Materi::with(['guru', 'mataPelajaran'])->findOrFail($id);
        return view('siswa.materi.show', compact('materi'));
    }
}
