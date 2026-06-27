<?php

namespace App\Http\Controllers\Guru;

use App\Http\Controllers\Controller;
use App\Models\Pengumpulan;

class DashboardController extends Controller
{
    public function index()
    {
        $guru        = auth()->user();
        $totalMateri = $guru->materi()->count();
        $totalTugas  = $guru->tugas()->count();
        $belumDinilai = Pengumpulan::where('status', 'sudah_kumpul')
            ->whereHas('tugas', fn($q) => $q->where('guru_id', $guru->id))
            ->count();

        return view('guru.dashboard', compact('totalMateri', 'totalTugas', 'belumDinilai'));
    }
}
