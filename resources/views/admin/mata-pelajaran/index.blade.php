@extends('admin.layout')

@section('title', 'Kelola Mata Pelajaran')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Mata Pelajaran</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar mata pelajaran yang tersedia</p>
    </div>
    <a href="{{ route('admin.mata-pelajaran.create') }}"
       class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Mata Pelajaran
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Nama Mata Pelajaran</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Materi</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Tugas</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($mapel as $m)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5 font-medium text-gray-800">{{ $m->nama_pelajaran }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $m->materi_count }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $m->tugas_count }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.mata-pelajaran.edit', $m) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1.5 rounded-lg hover:bg-primary-light">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        <form method="POST" action="{{ route('admin.mata-pelajaran.destroy', $m) }}"
                              onsubmit="return confirm('Hapus mata pelajaran {{ $m->nama_pelajaran }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-5 py-10 text-center text-gray-400 text-sm">Belum ada mata pelajaran.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
