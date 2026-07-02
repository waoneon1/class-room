<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        $kelasId = auth()->user()->kelas_id;
        $materi = Materi::with(['guru', 'mataPelajaran'])
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
