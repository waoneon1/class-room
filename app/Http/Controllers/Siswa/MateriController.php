<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;

class MateriController extends Controller
{
    public function index()
    {
        $materi = Materi::with(['guru', 'mataPelajaran'])->latest()->get();
        return view('siswa.materi.index', compact('materi'));
    }

    public function show($id)
    {
        $materi = Materi::with(['guru', 'mataPelajaran'])->findOrFail($id);
        return view('siswa.materi.show', compact('materi'));
    }
}
