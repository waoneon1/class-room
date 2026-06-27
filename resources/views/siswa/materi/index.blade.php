@extends('siswa.layout')

@section('title', 'Materi')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Materi</h1>
    <p class="text-sm text-gray-500 mt-0.5">Materi pembelajaran dari guru</p>
</div>

<div class="space-y-4 max-w-2xl">
    @forelse($materi as $m)
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Materi</span>
        <h3 class="font-bold text-gray-800 mt-2">{{ $m->judul }}</h3>
        @if($m->deskripsi)
            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($m->deskripsi, 120) }}</p>
        @endif

        <div class="flex items-center gap-2 mt-2">
            <span class="bg-primary-light text-primary text-xs px-2 py-0.5 rounded-full">{{ $m->mataPelajaran->nama_pelajaran }}</span>
        </div>

        @if($m->file_materi)
        <div class="bg-gray-50 rounded-xl px-4 py-2.5 mt-3 text-sm text-gray-600 flex items-center gap-2 w-fit">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ basename($m->file_materi) }}
        </div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($m->guru->nama_lengkap, 0, 1)) }}
                </div>
                <span>{{ $m->guru->nama_lengkap }}</span>
                <span>·</span>
                <span>{{ $m->created_at->format('d M Y') }}</span>
            </div>
            <a href="{{ route('siswa.materi.show', $m) }}"
               class="text-primary hover:underline text-xs font-medium">Lihat →</a>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada materi.
    </div>
    @endforelse
</div>
@endsection
