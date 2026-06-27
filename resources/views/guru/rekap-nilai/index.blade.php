@extends('guru.layout')

@section('title', 'Rekap Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Rekap Nilai</h1>
    <p class="text-sm text-gray-500 mt-0.5">Nilai siswa yang sudah dinilai</p>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('guru.rekap-nilai.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Semester</label>
            <select name="semester" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="1" {{ $semester == 1 ? 'selected' : '' }}>Semester 1</option>
                <option value="2" {{ $semester == 2 ? 'selected' : '' }}>Semester 2</option>
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Tahun Ajaran</label>
            <input type="text" name="tahun_ajaran" value="{{ $tahunAjaran }}"
                class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                placeholder="2025/2026">
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Mata Pelajaran</label>
            <select name="mata_pelajaran_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua</option>
                @foreach($mapel as $m)
                    <option value="{{ $m->id }}" {{ request('mata_pelajaran_id') == $m->id ? 'selected' : '' }}>{{ $m->nama_pelajaran }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
            Tampilkan
        </button>
    </form>
</div>

@if($rekap->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada data nilai untuk filter yang dipilih.
    </div>
@else
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Siswa</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Tugas</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Mata Pelajaran</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Nilai</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Terlambat</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($rekap as $siswaId => $nilaiList)
            @foreach($nilaiList as $n)
            <tr class="hover:bg-gray-50 transition-colors">
                @if($loop->first)
                <td class="px-5 py-4 align-top" rowspan="{{ $nilaiList->count() }}">
                    <p class="font-medium text-gray-800">{{ $n->siswa->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">{{ $n->siswa->username }}</p>
                </td>
                @endif
                <td class="px-5 py-4">{{ $n->tugas->judul }}</td>
                <td class="px-5 py-4 text-gray-500">{{ $n->tugas->mataPelajaran->nama_pelajaran }}</td>
                <td class="px-5 py-4 text-center">
                    <span class="font-bold {{ $n->nilai >= 75 ? 'text-green-600' : ($n->nilai >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                        {{ $n->nilai }}
                    </span>
                </td>
                <td class="px-5 py-4 text-center">
                    @if($n->terlambat)
                        <span class="bg-red-100 text-red-600 text-xs px-2 py-0.5 rounded-full">Ya</span>
                    @else
                        <span class="text-gray-300">—</span>
                    @endif
                </td>
            </tr>
            @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@endif
@endsection
