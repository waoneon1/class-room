@extends('admin.layout')

@section('title', 'Edit Kelas')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.kelas.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-xl font-bold text-gray-800">Edit Kelas</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-md">
    <form method="POST" action="{{ route('admin.kelas.update', $kelas) }}">
        @csrf @method('PUT')
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Kelas</label>
            <input type="text" name="nama_kelas" value="{{ old('nama_kelas', $kelas->nama_kelas) }}"
                class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nama_kelas') border-red-400 @enderror"
                required autofocus>
            @error('nama_kelas')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
        </div>
        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Perbarui</button>
            <a href="{{ route('admin.kelas.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">Batal</a>
        </div>
    </form>
</div>
@endsection
