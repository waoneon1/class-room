@extends('guru.layout')

@section('title', 'Edit Tugas')

@section('content')
<div class="mb-6">
    <a href="{{ route('guru.tugas.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-xl font-bold text-gray-800">Edit Tugas</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('guru.tugas.update', $tugas) }}" enctype="multipart/form-data">
        @csrf @method('PUT')

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Pelajaran</label>
                <select name="mata_pelajaran_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('mata_pelajaran_id') border-red-400 @enderror"
                    required>
                    <option value="">Pilih mata pelajaran</option>
                    @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ old('mata_pelajaran_id', $tugas->mata_pelajaran_id) == $m->id ? 'selected' : '' }}>{{ $m->nama_pelajaran }}</option>
                    @endforeach
                </select>
                @error('mata_pelajaran_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Tugas</label>
                <input type="text" name="judul" value="{{ old('judul', $tugas->judul) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('judul') border-red-400 @enderror"
                    required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi / Instruksi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('deskripsi') border-red-400 @enderror">{{ old('deskripsi', $tugas->deskripsi) }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">File Soal</label>
                @if($tugas->file_tugas)
                <div class="bg-gray-50 rounded-xl px-4 py-2.5 mb-2 text-sm text-gray-600 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    File saat ini: {{ basename($tugas->file_tugas) }}
                </div>
                @endif
                <input type="file" name="file_tugas" accept=".pdf,.doc,.docx,.xlsx,.xls,.jpg,.jpeg,.png"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('file_tugas') border-red-400 @enderror">
                <p class="text-xs text-gray-400 mt-1">Kosongkan jika tidak ingin mengganti file. Maks 10MB.</p>
                @error('file_tugas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deadline <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="datetime-local" name="deadline"
                    value="{{ old('deadline', $tugas->deadline ? $tugas->deadline->format('Y-m-d\TH:i') : '') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('deadline') border-red-400 @enderror">
                @error('deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Semester</label>
                    <select name="semester"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('semester') border-red-400 @enderror"
                        required>
                        <option value="1" {{ old('semester', $tugas->semester) == 1 ? 'selected' : '' }}>Semester 1</option>
                        <option value="2" {{ old('semester', $tugas->semester) == 2 ? 'selected' : '' }}>Semester 2</option>
                    </select>
                    @error('semester')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Tahun Ajaran</label>
                    <input type="text" name="tahun_ajaran" value="{{ old('tahun_ajaran', $tugas->tahun_ajaran) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('tahun_ajaran') border-red-400 @enderror"
                        required>
                    @error('tahun_ajaran')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Perbarui</button>
            <a href="{{ route('guru.tugas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
