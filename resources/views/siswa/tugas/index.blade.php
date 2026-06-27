@extends('siswa.layout')

@section('title', 'Tugas')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Tugas</h1>
    <p class="text-sm text-gray-500 mt-0.5">Daftar tugas dari guru</p>
</div>

<div class="space-y-4 max-w-2xl">
    @forelse($tugasList as $t)
    @php $status = $statusMap->get($t->id); @endphp
    <div class="bg-white rounded-2xl shadow-sm p-5">
        <div class="flex items-start justify-between gap-3">
            <div class="flex-1 min-w-0">
                <div class="flex flex-wrap items-center gap-2 mb-2">
                    <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">Tugas</span>
                    @if(!$status)
                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">Belum Dikumpulkan</span>
                    @elseif($status === 'sudah_dinilai')
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Sudah Dinilai</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Menunggu Penilaian</span>
                    @endif
                </div>

                <h3 class="font-bold text-gray-800">{{ $t->judul }}</h3>
                @if($t->deskripsi)
                    <p class="text-sm text-gray-500 mt-1">{{ Str::limit($t->deskripsi, 100) }}</p>
                @endif

                @if($t->deadline)
                <div class="mt-2 text-xs flex items-center gap-1 {{ now()->gt($t->deadline) ? 'text-red-500' : 'text-green-600' }}">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Deadline: {{ $t->deadline->format('d M Y, H:i') }}
                    @if(now()->gt($t->deadline)) <span class="font-medium">(Sudah lewat)</span> @endif
                </div>
                @endif

                <div class="flex items-center gap-2 mt-3 text-xs text-gray-400">
                    <span class="bg-primary-light text-primary px-2 py-0.5 rounded-full">{{ $t->mataPelajaran->nama_pelajaran }}</span>
                    <span>·</span>
                    <span>{{ $t->guru->nama_lengkap }}</span>
                    <span>·</span>
                    <span>{{ $t->created_at->format('d M Y') }}</span>
                </div>
            </div>

            <a href="{{ route('siswa.tugas.show', $t) }}"
               class="shrink-0 text-primary hover:underline text-xs font-medium mt-1">
                {{ $status ? 'Lihat →' : 'Kerjakan →' }}
            </a>
        </div>
    </div>
    @empty
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada tugas.
    </div>
    @endforelse
</div>
@endsection
