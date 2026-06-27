<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;

class NilaiController extends Controller
{
    public function index()
    {
        $nilaiList = Pengumpulan::with(['tugas.mataPelajaran', 'tugas.guru'])
            ->where('siswa_id', auth()->id())
            ->where('status', 'sudah_dinilai')
            ->latest()
            ->get();

        return view('siswa.nilai.index', compact('nilaiList'));
    }
}
