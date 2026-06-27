@extends('guru.layout')

@section('title', 'Tambah Materi')

@section('content')
<div class="mb-6">
    <a href="{{ route('guru.materi.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-xl font-bold text-gray-800">Tambah Materi</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Mata Pelajaran</label>
                <select name="mata_pelajaran_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('mata_pelajaran_id') border-red-400 @enderror"
                    required>
                    <option value="">Pilih mata pelajaran</option>
                    @foreach($mapel as $m)
                        <option value="{{ $m->id }}" {{ old('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_pelajaran }}</option>
                    @endforeach
                </select>
                @error('mata_pelajaran_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Materi</label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('judul') border-red-400 @enderror"
                    placeholder="Judul materi" required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('deskripsi') border-red-400 @enderror"
                    placeholder="Deskripsi singkat materi">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">File Materi <span class="text-gray-400 font-normal">(opsional — pdf, doc, xlsx, jpg, png, maks 10MB)</span></label>
                <input type="file" name="file_materi" accept=".pdf,.doc,.docx,.xlsx,.xls,.jpg,.jpeg,.png"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('file_materi') border-red-400 @enderror">
                @error('file_materi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Simpan</button>
            <a href="{{ route('guru.materi.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
