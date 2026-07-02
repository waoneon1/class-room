@extends('guru.layout')

@section('title', 'Tambah Tugas')

@section('content')
<div class="mb-6">
    <a href="{{ route('guru.tugas.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-xl font-bold text-gray-800">Tambah Tugas</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('guru.tugas.store') }}" enctype="multipart/form-data">
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Kelas <span class="text-red-500">*</span></label>
                @if($kelas->count() === 1)
                    <div class="p-4 border border-gray-200 rounded-xl bg-gray-50 flex items-center justify-between">
                        <div>
                            <p class="font-bold text-gray-800">{{ $kelas->first()->nama_kelas }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">Otomatis terpilih karena Anda hanya mengajar di satu kelas.</p>
                        </div>
                        <div class="w-6 h-6 rounded-full bg-green-100 text-green-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <input type="hidden" name="kelas_id[]" value="{{ $kelas->first()->id }}">
                    </div>
                @else
                    <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($kelas as $k)
                        <label class="flex items-center gap-2 p-3 border border-gray-200 rounded-xl cursor-pointer hover:bg-gray-50 transition-colors">
                            <input type="checkbox" name="kelas_id[]" value="{{ $k->id }}"
                                class="rounded text-primary focus:ring-primary w-4 h-4"
                                {{ (is_array(old('kelas_id')) && in_array($k->id, old('kelas_id'))) ? 'checked' : '' }}>
                            <span class="text-sm text-gray-700 font-medium">{{ $k->nama_kelas }}</span>
                        </label>
                        @endforeach
                    </div>
                @endif
                @error('kelas_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Judul Tugas</label>
                <input type="text" name="judul" value="{{ old('judul') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('judul') border-red-400 @enderror"
                    placeholder="Judul tugas" required>
                @error('judul')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deskripsi / Instruksi <span class="text-gray-400 font-normal">(opsional)</span></label>
                <textarea name="deskripsi" rows="3"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('deskripsi') border-red-400 @enderror"
                    placeholder="Petunjuk pengerjaan tugas">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">File Soal <span class="text-gray-400 font-normal">(opsional — pdf, doc, xlsx, jpg, png, maks 10MB)</span></label>
                <input type="file" name="file_tugas" accept=".pdf,.doc,.docx,.xlsx,.xls,.jpg,.jpeg,.png"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('file_tugas') border-red-400 @enderror">
                @error('file_tugas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Deadline <span class="text-gray-400 font-normal">(opsional)</span></label>
                <input type="datetime-local" name="deadline" value="{{ old('deadline') }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('deadline') border-red-400 @enderror">
                @error('deadline')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>


        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Simpan</button>
            <a href="{{ route('guru.tugas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
