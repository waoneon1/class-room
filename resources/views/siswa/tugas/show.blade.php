@extends('siswa.layout')

@section('title', $tugas->judul)

@section('content')
<div class="mb-6">
    <a href="{{ route('siswa.tugas.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
</div>

<div class="grid grid-cols-1 md:grid-cols-5 gap-6 max-w-4xl">
    {{-- Info Tugas --}}
    <div class="md:col-span-3 space-y-4">
        <div class="bg-white rounded-2xl shadow-sm p-6">
            <div class="flex flex-wrap items-center gap-2 mb-3">
                <span class="bg-blue-100 text-blue-700 text-xs px-3 py-1 rounded-full font-medium">Tugas</span>
                @if($pengumpulan)
                    @if($pengumpulan->status === 'sudah_dinilai')
                        <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full">Sudah Dinilai</span>
                    @else
                        <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1 rounded-full">Menunggu Penilaian</span>
                    @endif
                    @if($pengumpulan->terlambat)
                        <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">Terlambat</span>
                    @endif
                @else
                    <span class="bg-red-100 text-red-700 text-xs px-3 py-1 rounded-full">Belum Dikumpulkan</span>
                @endif
            </div>

            <h1 class="text-xl font-bold text-gray-800">{{ $tugas->judul }}</h1>

            <div class="flex flex-wrap items-center gap-2 mt-2 text-sm text-gray-500">
                <span class="bg-primary-light text-primary text-xs px-2 py-0.5 rounded-full">{{ $tugas->mataPelajaran->nama_pelajaran }}</span>
                <span>·</span>
                <div class="flex items-center gap-1.5">
                    <div class="w-5 h-5 rounded-full bg-primary flex items-center justify-center text-white text-xs font-bold">
                        {{ strtoupper(substr($tugas->guru->nama_lengkap, 0, 1)) }}
                    </div>
                    <span class="text-xs">{{ $tugas->guru->nama_lengkap }}</span>
                </div>
            </div>

            @if($tugas->deadline)
            <div class="mt-3 flex items-center gap-1.5 text-sm font-medium {{ now()->gt($tugas->deadline) ? 'text-red-500' : 'text-green-600' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Deadline: {{ $tugas->deadline->format('d M Y, H:i') }}
                @if(now()->gt($tugas->deadline)) (sudah lewat) @endif
            </div>
            @endif

            @if($tugas->deskripsi)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-sm text-gray-700 leading-relaxed">{{ $tugas->deskripsi }}</p>
            </div>
            @endif

            @if($tugas->file_tugas)
            <div class="mt-4 pt-4 border-t border-gray-100">
                <p class="text-xs font-medium text-gray-500 mb-3">File Soal</p>
                <a href="{{ Storage::url($tugas->file_tugas) }}" target="_blank"
                   class="inline-flex items-center gap-3 bg-primary-light text-primary px-5 py-3 rounded-xl hover:bg-primary hover:text-white transition-colors font-medium text-sm">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    Download Soal
                </a>
            </div>
            @endif
        </div>

        {{-- Foto yang sudah dikumpulkan --}}
        @if($pengumpulan && $pengumpulan->files->isNotEmpty())
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-4 text-sm">Foto Jawaban yang Dikumpulkan</h2>
            <div class="space-y-3">
                @foreach($pengumpulan->files as $file)
                <div>
                    <img src="{{ Storage::url($file->file_path) }}"
                         alt="Foto jawaban {{ $loop->iteration }}"
                         class="w-full rounded-xl border border-gray-100 object-contain max-h-[500px]"
                         loading="lazy">
                    @if($pengumpulan->files->count() > 1)
                        <p class="text-xs text-gray-400 text-center mt-1">Foto {{ $loop->iteration }}</p>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    {{-- Sidebar: Upload / Nilai --}}
    <div class="md:col-span-2">
        @if(!$pengumpulan)
        {{-- Form Upload --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 sticky top-24">
            <h2 class="font-semibold text-gray-700 mb-1 text-sm">Kumpulkan Jawaban</h2>
            <p class="text-xs text-gray-400 mb-4">Upload foto lembar jawaban kamu (jpg/jpeg/png, maks 5MB/foto)</p>

            @if($tugas->deadline && now()->gt($tugas->deadline))
            <div class="bg-red-50 border border-red-100 rounded-xl px-4 py-2.5 mb-4 text-xs text-red-600">
                ⚠ Deadline sudah lewat. Pengumpulan akan ditandai <strong>terlambat</strong>.
            </div>
            @endif

            <form method="POST" action="{{ route('siswa.tugas.kumpul', $tugas) }}"
                  enctype="multipart/form-data">
                @csrf

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">
                        Foto Jawaban
                        <span class="text-gray-400 font-normal">(bisa lebih dari 1)</span>
                    </label>
                    <input type="file" name="foto[]" accept="image/jpg,image/jpeg,image/png"
                           multiple required
                           class="w-full border border-gray-200 rounded-xl px-3 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('foto') border-red-400 @enderror @error('foto.*') border-red-400 @enderror">
                    @error('foto')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    @error('foto.*')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <button type="submit"
                    class="w-full mt-5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                    Kumpulkan Tugas
                </button>
            </form>
        </div>

        @elseif($pengumpulan->status === 'sudah_dinilai')
        {{-- Tampilkan nilai --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 sticky top-24">
            <h2 class="font-semibold text-gray-700 mb-4 text-sm">Nilai Kamu</h2>
            <div class="bg-primary-light rounded-xl px-5 py-4 text-center mb-4">
                <p class="text-5xl font-bold text-primary">{{ $pengumpulan->nilai }}</p>
                <p class="text-xs text-gray-500 mt-1">dari 100</p>
            </div>
            @if($pengumpulan->catatan)
            <div class="bg-gray-50 rounded-xl px-4 py-3">
                <p class="text-xs font-medium text-gray-500 mb-1">Catatan Guru</p>
                <p class="text-sm text-gray-700 italic">{{ $pengumpulan->catatan }}</p>
            </div>
            @endif
            <p class="text-xs text-gray-400 mt-4 text-center">
                Dikumpulkan {{ $pengumpulan->created_at->format('d M Y, H:i') }}
            </p>
        </div>

        @else
        {{-- Menunggu penilaian --}}
        <div class="bg-white rounded-2xl shadow-sm p-5 sticky top-24">
            <div class="text-center py-6">
                <div class="w-14 h-14 bg-yellow-100 rounded-full flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <p class="font-semibold text-gray-700">Menunggu Penilaian</p>
                <p class="text-xs text-gray-400 mt-1">
                    Dikumpulkan {{ $pengumpulan->created_at->format('d M Y, H:i') }}
                </p>
                @if($pengumpulan->terlambat)
                    <span class="mt-2 inline-block bg-red-100 text-red-600 text-xs px-3 py-1 rounded-full">Terlambat</span>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
