@extends('siswa.layout_sidebar')

@section('title', 'Rincian Nilai - ' . $mataPelajaran->nama_pelajaran)

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.nilai.index') }}" class="inline-flex items-center text-sm text-gray-500 hover:text-primary mb-4 transition-colors">
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
        Kembali ke Rekap Nilai
    </a>
    <h1 class="text-xl font-bold text-gray-800">Rincian Nilai: {{ $mataPelajaran->nama_pelajaran }}</h1>
    <p class="text-sm text-gray-500 mt-0.5">Daftar nilai tugas yang sudah diperiksa oleh guru</p>
</div>

@if($nilaiList->isEmpty())
<div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm max-w-2xl">
    Belum ada nilai untuk mata pelajaran ini. Kumpulkan tugas dan tunggu guru menilai.
</div>
@else
<div class="space-y-4 max-w-2xl">
    @foreach($nilaiList as $n)
    <div class="bg-white rounded-2xl shadow-sm p-5 hover:shadow transition-shadow">
        <div class="flex items-start gap-4">
            {{-- Nilai bubble --}}
            <div class="shrink-0 w-16 h-16 rounded-2xl flex items-center justify-center
                {{ $n->nilai >= 75 ? 'bg-green-100' : ($n->nilai >= 60 ? 'bg-yellow-100' : 'bg-red-100') }}">
                <span class="text-2xl font-bold {{ $n->nilai >= 75 ? 'text-green-600' : ($n->nilai >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                    {{ $n->nilai }}
                </span>
            </div>

            <div class="flex-1 min-w-0">
                <div class="flex items-center gap-2 mb-1">
                    @if($n->terlambat)
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">Terlambat</span>
                    @endif
                </div>
                <h3 class="font-bold text-gray-800">{{ $n->tugas->judul }}</h3>
                <p class="text-xs text-gray-400 mt-0.5">{{ $n->tugas->guru->nama_lengkap }} · {{ $n->tugas->periode?->nama_periode ?? 'Tanpa Periode' }}</p>

                @if($n->catatan)
                <div class="mt-3 bg-gray-50 rounded-xl px-4 py-2.5">
                    <p class="text-xs font-medium text-gray-500 mb-0.5">Catatan Guru</p>
                    <p class="text-sm text-gray-700 italic">{{ $n->catatan }}</p>
                </div>
                @endif

                <p class="text-xs text-gray-400 mt-2">Dinilai {{ $n->updated_at->format('d M Y') }}</p>
            </div>
        </div>
    </div>
    @endforeach

    {{-- Rata-rata Pelajaran Ini --}}
    <div class="bg-primary rounded-2xl p-5 text-white">
        <p class="text-primary-light text-sm">Rata-rata Nilai ({{ $mataPelajaran->nama_pelajaran }})</p>
        <p class="text-4xl font-bold mt-1">{{ number_format($nilaiList->avg('nilai'), 1) }}</p>
        <p class="text-primary-light text-xs mt-1">dari {{ $nilaiList->count() }} tugas dinilai</p>
    </div>
</div>
@endif
@endsection
