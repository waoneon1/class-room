@extends('siswa.layout')

@section('title', 'Home')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Selamat datang, {{ auth()->user()->nama_lengkap }} 👋</h1>
    <p class="text-sm text-gray-500 mt-0.5">Berikut aktivitas terbaru dari guru kamu</p>
</div>

<div class="space-y-4 max-w-2xl">
    @forelse($feed as $item)
    @php $obj = $item['obj']; $type = $item['type']; @endphp
    <div class="bg-white rounded-2xl shadow-sm p-5">
        @if($type === 'materi')
            <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Materi</span>
        @else
            <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">Tugas</span>
        @endif

        <h3 class="font-bold text-gray-800 mt-2">{{ $obj->judul }}</h3>

        @if($obj->deskripsi)
            <p class="text-sm text-gray-500 mt-1">{{ Str::limit($obj->deskripsi, 120) }}</p>
        @endif

        @php $fileCol = $type === 'materi' ? $obj->file_materi : $obj->file_tugas; @endphp
        @if($fileCol)
        <div class="bg-gray-50 rounded-xl px-4 py-2.5 mt-3 text-sm text-gray-600 flex items-center gap-2 w-fit">
            <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            {{ basename($fileCol) }}
        </div>
        @endif

        @if($type === 'tugas' && $obj->deadline)
        <div class="mt-2 text-xs {{ now()->gt($obj->deadline) ? 'text-red-500' : 'text-green-600' }} flex items-center gap-1">
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Deadline: {{ $obj->deadline->format('d M Y, H:i') }}
            {{ now()->gt($obj->deadline) ? '(sudah lewat)' : '' }}
        </div>
        @endif

        <div class="flex items-center justify-between mt-4">
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <div class="w-7 h-7 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold">
                    {{ strtoupper(substr($obj->guru->nama_lengkap, 0, 1)) }}
                </div>
                <span>{{ $obj->guru->nama_lengkap }}</span>
                <span>·</span>
                <span>{{ $obj->created_at->format('d M Y') }}</span>
            </div>
            @if($type === 'materi')
                <a href="{{ route('siswa.materi.show', $obj) }}"
                   class="text-primary hover:underline text-xs font-medium">Lihat →</a>
            @else
                <a href="{{ route('siswa.tugas.show', $obj) }}"
                   class="text-primary hover:underline text-xs font-medium">Kerjakan →</a>
            @endif
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada materi atau tugas dari guru.
    </div>
    @endforelse
</div>
@endsection
