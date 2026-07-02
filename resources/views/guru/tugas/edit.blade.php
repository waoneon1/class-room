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
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas</label>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($kelas as $k)
                    <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                        <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}"
                            class="rounded text-primary focus:ring-primary w-4 h-4"
                            {{ (is_array(old('kelas_id')) && in_array($k->id, old('kelas_id'))) || (!old('kelas_id') && $tugas->kelas->contains($k->id)) ? 'checked' : '' }}>
                        <span class="text-sm text-gray-700 font-medium">{{ $k->nama_kelas }}</span>
                    </label>
                    @endforeach
                </div>
                @error('kelas_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
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


        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Perbarui</button>
            <a href="{{ route('guru.tugas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
