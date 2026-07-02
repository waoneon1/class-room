@extends('guru.layout')

@section('title', 'Pengumpulan — ' . $tugas->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('guru.tugas.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Tugas
    </a>
    <h1 class="text-xl font-bold text-gray-800">{{ $tugas->judul }}</h1>
    <p class="text-sm text-gray-500 mt-0.5">{{ $tugas->mataPelajaran->nama_pelajaran }} · {{ $tugas->periode?->nama_periode ?? 'Tanpa Periode' }}</p>
</div>

{{-- Counter --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <div class="flex items-center gap-4">
        <div class="w-14 h-14 rounded-full bg-primary-light flex items-center justify-center">
            <span class="text-primary font-bold text-xl">{{ $pengumpulan->count() }}</span>
        </div>
        <div>
            <p class="text-2xl font-bold text-gray-800">{{ $pengumpulan->count() }} <span class="text-gray-400 font-normal text-lg">dari {{ $totalSiswa }}</span></p>
            <p class="text-sm text-gray-500">siswa sudah mengumpulkan</p>
        </div>
    </div>
</div>

{{-- List semua siswa dengan status --}}
@php
    $semuaSiswa = \App\Models\User::role('siswa')->orderBy('nama_lengkap')->get();
@endphp

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Nama Siswa</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Status</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Nilai</th>
                <th class="px-5 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($semuaSiswa as $siswa)
            @php $p = $pengumpulan->get($siswa->id); @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold shrink-0">
                            {{ strtoupper(substr($siswa->nama_lengkap, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</p>
                            <p class="text-xs text-gray-400">{{ $siswa->username }}</p>
                        </div>
                    </div>
                </td>
                <td class="px-5 py-4">
                    @if(!$p)
                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">Belum Dikumpulkan</span>
                    @elseif($p->status === 'sudah_dinilai')
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Sudah Dinilai</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Menunggu Penilaian</span>
                    @endif
                    @if($p && $p->terlambat)
                        <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full ml-1">Terlambat</span>
                    @endif
                </td>
                <td class="px-5 py-4">
                    @if($p && $p->nilai !== null)
                        <span class="font-bold text-gray-800">{{ $p->nilai }}</span>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-right">
                    @if($p)
                        <a href="{{ route('guru.pengumpulan.show', $p) }}"
                           class="text-primary hover:underline text-xs font-medium">Lihat →</a>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
