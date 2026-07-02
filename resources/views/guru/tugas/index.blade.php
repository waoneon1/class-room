@extends('guru.layout')

@section('title', 'Tugas')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Tugas</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar tugas yang telah kamu buat</p>
    </div>
    <a href="{{ route('guru.tugas.create') }}"
       class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Tugas
    </a>
</div>

<div class="space-y-4">
    @forelse($tugas as $t)
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-start justify-between gap-4">
            <div class="flex-1 min-w-0">
                <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">Tugas</span>
                <h3 class="font-bold text-gray-800 mt-2">{{ $t->judul }}</h3>
                @if($t->deskripsi)
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($t->deskripsi, 100) }}</p>
                @endif
                @if($t->file_tugas)
                <div class="bg-gray-50 rounded-xl px-4 py-2.5 mt-3 text-sm text-gray-600 flex items-center gap-2 w-fit">
                    <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    {{ basename($t->file_tugas) }}
                </div>
                @endif
                <div class="flex flex-wrap items-center gap-2 mt-3 text-xs text-gray-500">
                    <span class="bg-primary-light text-primary px-2 py-0.5 rounded-full">{{ $t->mataPelajaran->nama_pelajaran }}</span>
                    <span>{{ $t->periode?->nama_periode ?? 'Tanpa Periode' }}</span>
                    @if($t->deadline)
                        <span>·</span>
                        @if(now()->gt($t->deadline))
                            <span class="text-red-500">Deadline: {{ $t->deadline->format('d M Y H:i') }} (lewat)</span>
                        @else
                            <span class="text-green-600">Deadline: {{ $t->deadline->format('d M Y H:i') }}</span>
                        @endif
                    @endif
                </div>
            </div>
            <div class="flex items-center gap-2 shrink-0">
                <a href="{{ route('guru.pengumpulan.index', $t) }}"
                   class="text-gray-400 hover:text-blue-500 transition-colors p-1.5 rounded-lg hover:bg-blue-50" title="Lihat Pengumpulan">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </a>
                <a href="{{ route('guru.tugas.edit', $t) }}"
                   class="text-gray-400 hover:text-primary transition-colors p-1.5 rounded-lg hover:bg-primary-light">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                </a>
                <form method="POST" action="{{ route('guru.tugas.destroy', $t) }}"
                      onsubmit="return confirm('Hapus tugas ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada tugas. <a href="{{ route('guru.tugas.create') }}" class="text-primary hover:underline">Tambah tugas pertama</a>.
    </div>
    @endforelse
</div>
@endsection
