<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Guru;
use App\Http\Controllers\Siswa;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect('/login'));

// Auth
Route::get('/login', [LoginController::class, 'showLogin'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:admin'])->group(function () {
    Route::resource('pengguna', Admin\PenggunaController::class);
    Route::resource('kelas', Admin\KelasController::class);
    Route::resource('mata-pelajaran', Admin\MataPelajaranController::class);
    Route::resource('periode', Admin\PeriodeController::class);
    Route::get('rekap-nilai', [Admin\RekapNilaiController::class, 'index'])->name('rekap-nilai.index');
});

// Guru
Route::prefix('guru')->name('guru.')->middleware(['auth', 'role:guru'])->group(function () {
    Route::get('dashboard', [Guru\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('materi', Guru\MateriController::class);
    Route::resource('tugas', Guru\TugasController::class);
    Route::get('tugas/{tugas}/pengumpulan', [Guru\PengumpulanController::class, 'index'])->name('pengumpulan.index');
    Route::get('pengumpulan/{pengumpulan}', [Guru\PengumpulanController::class, 'show'])->name('pengumpulan.show');
    Route::post('pengumpulan/{pengumpulan}/nilai', [Guru\PengumpulanController::class, 'nilai'])->name('pengumpulan.nilai');
    Route::get('rekap-nilai', [Guru\RekapNilaiController::class, 'index'])->name('rekap-nilai.index');
});

// Siswa
Route::prefix('siswa')->name('siswa.')->middleware(['auth', 'role:siswa'])->group(function () {
    Route::get('dashboard', [Siswa\DashboardController::class, 'index'])->name('dashboard');
    Route::resource('materi', Siswa\MateriController::class)->only(['index', 'show']);
    Route::resource('tugas', Siswa\TugasController::class)->only(['index', 'show']);
    Route::post('tugas/{tugas}/kumpul', [Siswa\TugasController::class, 'kumpul'])->name('tugas.kumpul');
    Route::delete('tugas/{tugas}/batal', [Siswa\TugasController::class, 'batal'])->name('tugas.batal');
    Route::get('nilai', [Siswa\NilaiController::class, 'index'])->name('nilai.index');
});
