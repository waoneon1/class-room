<?php

namespace App\Http\Controllers\Siswa;

use App\Http\Controllers\Controller;
use App\Models\Materi;
use App\Models\Tugas;

class DashboardController extends Controller
{
    public function index()
    {
        $materi = Materi::with(['guru', 'mataPelajaran'])->latest()->get();
        $tugas  = Tugas::with(['guru', 'mataPelajaran'])->latest()->get();

        $feed = $materi->map(fn($m) => ['type' => 'materi', 'obj' => $m, 'created_at' => $m->created_at])
            ->concat($tugas->map(fn($t) => ['type' => 'tugas', 'obj' => $t, 'created_at' => $t->created_at]))
            ->sortByDesc('created_at')
            ->values();

        return view('siswa.dashboard', compact('feed'));
    }
}
