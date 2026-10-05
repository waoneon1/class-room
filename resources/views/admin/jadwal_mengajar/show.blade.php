@extends('admin.layout')

@section('title', 'Atur Pengajar Kelas ' . $kelas->nama_kelas)

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelas {{ $kelas->nama_kelas }}</h1>
        <p class="text-sm text-gray-500 mt-0.5">Atur Guru Pengajar per Mata Pelajaran (Periode: {{ $periodeAktif->nama_periode ?? '-' }})</p>
    </div>
    <a href="{{ route('admin.jadwal-mengajar.index') }}" class="text-gray-500 hover:text-gray-700 text-sm font-medium">
        &larr; Kembali
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6">
    <form action="{{ route('admin.jadwal-mengajar.store') }}" method="POST">
        @csrf
        <input type="hidden" name="kelas_id" value="{{ $kelas->id }}">
        
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-gray-100">
                    <th class="text-left px-4 py-3 text-gray-500 font-medium w-1/3">Mata Pelajaran</th>
                    <th class="text-left px-4 py-3 text-gray-500 font-medium">Guru Pengajar</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($mapels as $mapel)
                <tr>
                    <td class="px-4 py-4 font-medium text-gray-800">{{ $mapel->nama_pelajaran }}</td>
                    <td class="px-4 py-4">
                        <select name="guru[{{ $mapel->id }}]" class="w-full border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary">
                            <option value="">-- Kosong (Belum ada guru) --</option>
                            @foreach($gurus as $guru)
                                <option value="{{ $guru->id }}" {{ isset($jadwal[$mapel->id]) && $jadwal[$mapel->id]->user_id == $guru->id ? 'selected' : '' }}>
                                    {{ $guru->nama_lengkap }} {{ $guru->tipe_guru == 'wali' ? '(Wali Kelas)' : '(Mapel)' }}
                                </option>
                            @endforeach
                        </select>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Simpan Jadwal Mengajar
            </button>
        </div>
    </form>
</div>
@endsection
