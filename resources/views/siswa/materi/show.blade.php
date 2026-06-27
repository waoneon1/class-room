@extends('siswa.layout')

@section('title', $materi->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.materi.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-2xl">
    <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Materi</span>
    <h1 class="text-xl font-bold text-gray-800 mt-3">{{ $materi->judul }}</h1>

    <div class="flex items-center gap-2 mt-2 text-sm text-gray-500">
        <span class="bg-primary-light text-primary text-xs px-2 py-0.5 rounded-full">{{ $materi->mataPelajaran->nama_pelajaran }}</span>
        <span>·</span>
        <div class="flex items-center gap-1.5">
            <div class="w-6 h-6 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold">
                {{ strtoupper(substr($materi->guru->nama_lengkap, 0, 1)) }}
            </div>
            <span>{{ $materi->guru->nama_lengkap }}</span>
        </div>
        <span>·</span>
        <span>{{ $materi->created_at->format('d M Y') }}</span>
    </div>

    @if($materi->deskripsi)
    <div class="mt-5 pt-5 border-t border-gray-100">
        <p class="text-gray-700 text-sm leading-relaxed">{{ $materi->deskripsi }}</p>
    </div>
    @endif

    @if($materi->file_materi)
    <div class="mt-5 pt-5 border-t border-gray-100">
        <p class="text-xs font-medium text-gray-500 mb-3">File Materi</p>
        <a href="{{ Storage::url($materi->file_materi) }}" target="_blank"
           class="inline-flex items-center gap-3 bg-primary-light text-primary px-5 py-3 rounded-xl hover:bg-primary hover:text-white transition-colors font-medium text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
            </svg>
            Download {{ basename($materi->file_materi) }}
        </a>
    </div>
    @endif
</div>
@endsection
