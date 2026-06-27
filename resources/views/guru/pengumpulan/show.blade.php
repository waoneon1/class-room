@extends('guru.layout')

@section('title', 'Detail Pengumpulan')

@section('content')
<div class="mb-6">
    <a href="{{ route('guru.pengumpulan.index', $pengumpulan->tugas_id) }}"
       class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Pengumpulan
    </a>
    <div class="flex items-start gap-3">
        <div>
            <h1 class="text-xl font-bold text-gray-800">{{ $pengumpulan->siswa->nama_lengkap }}</h1>
            <p class="text-sm text-gray-500">{{ $pengumpulan->tugas->judul }}</p>
        </div>
        <div class="ml-auto flex items-center gap-2">
            @if($pengumpulan->status === 'sudah_dinilai')
                <span class="bg-green-100 text-green-700 text-xs px-3 py-1.5 rounded-full font-medium">Sudah Dinilai</span>
            @else
                <span class="bg-yellow-100 text-yellow-700 text-xs px-3 py-1.5 rounded-full font-medium">Menunggu Penilaian</span>
            @endif
            @if($pengumpulan->terlambat)
                <span class="bg-red-100 text-red-700 text-xs px-3 py-1.5 rounded-full font-medium">Terlambat</span>
            @endif
        </div>
    </div>
</div>

<div class="grid grid-cols-1 md:grid-cols-5 gap-6">
    {{-- Foto Jawaban (kiri, lebih lebar) --}}
    <div class="md:col-span-3">
        <div class="bg-white rounded-2xl shadow-sm p-5">
            <h2 class="font-semibold text-gray-700 mb-4 text-sm">Foto Jawaban</h2>
            @if($pengumpulan->files->isEmpty())
                <p class="text-sm text-gray-400 text-center py-8">Tidak ada foto yang diunggah.</p>
            @else
                <div class="space-y-3">
                    @foreach($pengumpulan->files as $file)
                    <div>
                        <img src="{{ Storage::url($file->file_path) }}"
                             alt="Foto jawaban {{ $loop->iteration }}"
                             class="w-full rounded-xl border border-gray-100 object-contain max-h-[600px]"
                             loading="lazy">
                        @if($pengumpulan->files->count() > 1)
                        <p class="text-xs text-gray-400 text-center mt-1">Foto {{ $loop->iteration }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Form Nilai (kanan) --}}
    <div class="md:col-span-2">
        <div class="bg-white rounded-2xl shadow-sm p-5 sticky top-24">
            <h2 class="font-semibold text-gray-700 mb-4 text-sm">Beri Nilai</h2>

            @if($pengumpulan->status === 'sudah_dinilai')
            <div class="bg-primary-light rounded-xl px-4 py-3 mb-5">
                <p class="text-xs text-gray-500 mb-0.5">Nilai saat ini</p>
                <p class="text-3xl font-bold text-primary">{{ $pengumpulan->nilai }}</p>
                @if($pengumpulan->catatan)
                    <p class="text-xs text-gray-600 mt-2 italic">{{ $pengumpulan->catatan }}</p>
                @endif
            </div>
            @endif

            <form method="POST" action="{{ route('guru.pengumpulan.nilai', $pengumpulan) }}">
                @csrf

                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Nilai <span class="text-gray-400 font-normal">(0–100)</span>
                        </label>
                        <input type="number" name="nilai"
                            value="{{ old('nilai', $pengumpulan->nilai) }}"
                            min="0" max="100"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nilai') border-red-400 @enderror"
                            placeholder="0–100" required>
                        @error('nilai')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Catatan <span class="text-gray-400 font-normal">(opsional)</span>
                        </label>
                        <textarea name="catatan" rows="4"
                            class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('catatan') border-red-400 @enderror"
                            placeholder="Catatan untuk siswa...">{{ old('catatan', $pengumpulan->catatan) }}</textarea>
                        @error('catatan')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>

                <button type="submit"
                    class="w-full mt-5 bg-primary hover:bg-primary-dark text-white text-sm font-semibold py-2.5 rounded-xl transition-colors">
                    {{ $pengumpulan->status === 'sudah_dinilai' ? 'Perbarui Nilai' : 'Simpan Nilai' }}
                </button>
            </form>

            <div class="mt-4 pt-4 border-t border-gray-100 text-xs text-gray-400 space-y-1">
                <p>Dikumpulkan: {{ $pengumpulan->created_at->format('d M Y, H:i') }}</p>
                @if($pengumpulan->terlambat && $pengumpulan->tugas->deadline)
                    <p class="text-red-400">Deadline: {{ $pengumpulan->tugas->deadline->format('d M Y, H:i') }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
