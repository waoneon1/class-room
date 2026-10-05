@extends('admin.layout')

@section('title', 'Rekap Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Rekap Nilai</h1>
    <p class="text-sm text-gray-500 mt-0.5">Nilai siswa berdasarkan semester dan tahun ajaran</p>
</div>

{{-- Filter --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('admin.rekap-nilai.index') }}" class="flex flex-wrap gap-3 items-end">
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Periode</label>
            <select name="periode_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Pilih Periode</option>
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ ($filter['periode_id'] ?? '') == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_periode }} {{ $p->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-600 mb-1.5">Mata Pelajaran</label>
            <select name="mata_pelajaran_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                <option value="">Semua</option>
                @foreach($mapel as $m)
                    <option value="{{ $m->id }}" {{ ($filter['mata_pelajaran_id'] ?? '') == $m->id ? 'selected' : '' }}>
                        {{ $m->nama_pelajaran }}
                    </option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
            Tampilkan
        </button>
        @if(array_filter($filter))
        <a href="{{ route('admin.rekap-nilai.index') }}" class="text-sm text-gray-500 hover:text-primary py-2">Reset</a>
        @endif
    </form>
</div>

{{-- Tabel --}}
@if($rekap->isNotEmpty())
<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Siswa</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Kelas</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Tugas</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Mata Pelajaran</th>
                <th class="text-center px-5 py-3.5 text-gray-500 font-medium">Nilai</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Tanggal</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($rekap as $siswaId => $pengumpulanList)
                @foreach($pengumpulanList as $p)
                <tr class="hover:bg-gray-50">
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 bg-primary-light rounded-full flex items-center justify-center text-primary font-bold text-xs">
                                {{ strtoupper(substr($p->siswa->nama_lengkap, 0, 1)) }}
                            </div>
                            <span class="font-medium text-gray-800">{{ $p->siswa->nama_lengkap }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $p->siswa->kelas?->nama_kelas ?? '—' }}</td>
                    <td class="px-5 py-3.5 text-gray-700">{{ $p->tugas->judul }}</td>
                    <td class="px-5 py-3.5 text-gray-600">{{ $p->tugas->mataPelajaran->nama_pelajaran }}</td>
                    <td class="px-5 py-3.5 text-center">
                        <span class="inline-block font-bold text-base {{ $p->nilai >= 75 ? 'text-green-600' : 'text-red-500' }}">
                            {{ $p->nilai }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5 text-gray-500 text-xs">{{ $p->updated_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>
</div>
@elseif(array_filter($filter))
<div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
    Tidak ada data nilai untuk filter ini.
</div>
@else
<div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
    Pilih semester dan tahun ajaran untuk menampilkan rekap nilai.
</div>
@endif
@endsection
