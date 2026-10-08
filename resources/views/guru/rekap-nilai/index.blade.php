@extends('guru.layout')

@section('title', 'Rekap Nilai')

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Rekap Nilai</h1>
        <p class="text-sm text-gray-500 mt-0.5">Ringkasan nilai per siswa (Satu baris per siswa)</p>
    </div>
    
    <form action="{{ route('guru.rekap-nilai.index') }}" method="GET" class="flex items-center">
        <!-- Retain other filters -->
        <input type="hidden" name="periode_id" value="{{ $periodeId }}">
        <input type="hidden" name="kelas_id" value="{{ $kelasId }}">
        <input type="hidden" name="mata_pelajaran_id" value="{{ $mataPelajaranId }}">
        
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama siswa..." 
            class="border border-gray-200 rounded-l-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary w-full sm:w-64">
        <button type="submit" class="bg-gray-100 border border-l-0 border-gray-200 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-r-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        </button>
    </form>
</div>

{{-- Filter Utama --}}
<div class="bg-white rounded-2xl shadow-sm p-5 mb-6">
    <form method="GET" action="{{ route('guru.rekap-nilai.index') }}" class="flex flex-wrap gap-3 items-end">
        @if(request('search'))
            <input type="hidden" name="search" value="{{ request('search') }}">
        @endif
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Periode</label>
            <select name="periode_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" onchange="this.form.submit()">
                @foreach($periodes as $p)
                    <option value="{{ $p->id }}" {{ $periodeId == $p->id ? 'selected' : '' }}>
                        {{ $p->nama_periode }} {{ $p->is_active ? '(Aktif)' : '' }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Kelas</label>
            <select name="kelas_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" onchange="this.form.submit()">
                @foreach($kelasGuru as $k)
                    <option value="{{ $k->id }}" {{ $kelasId == $k->id ? 'selected' : '' }}>
                        {{ $k->nama_kelas }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-xs font-medium text-gray-500 mb-1">Mata Pelajaran</label>
            <select name="mata_pelajaran_id" class="border border-gray-200 rounded-xl px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary" onchange="this.form.submit()">
                @forelse($mapel as $m)
                    <option value="{{ $m->id }}" {{ $mataPelajaranId == $m->id ? 'selected' : '' }}>{{ $m->nama_pelajaran }}</option>
                @empty
                    <option value="">(Tidak ada mapel)</option>
                @endforelse
            </select>
        </div>
        <button type="submit" class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2 rounded-xl transition-colors">
            Tampilkan
        </button>
    </form>
</div>

@if($kelasGuru->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Anda belum terdaftar mengajar di kelas manapun.
    </div>
@elseif($siswaList->isEmpty())
    <div class="bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada data siswa untuk filter yang dipilih.
    </div>
@else
<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Siswa</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Nilai Terbaru</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Rata-rata Nilai</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Tugas Terkumpul</th>
                <th class="text-right px-5 py-3">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @foreach($siswaList as $siswa)
            @php 
                $nilaiList = $pengumpulan->get($siswa->id, collect());
                $sudahDinilai = $nilaiList->whereNotNull('nilai');
                
                $rataRata = $sudahDinilai->count() > 0 ? round($sudahDinilai->avg('nilai'), 1) : null;
                $terbaru = $nilaiList->sortByDesc(fn($n) => $n->tugas->deadline ?? $n->created_at)->first();
            @endphp
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-4">
                    <p class="font-medium text-gray-800">{{ $siswa->nama_lengkap }}</p>
                    <p class="text-xs text-gray-400">{{ $siswa->username }}</p>
                </td>
                <td class="px-5 py-4">
                    @if($terbaru)
                        <div class="text-xs text-gray-500 mb-0.5 truncate max-w-[200px]">{{ $terbaru->tugas->judul }}</div>
                        @if($terbaru->nilai !== null)
                            <span class="font-bold {{ $terbaru->nilai >= 75 ? 'text-green-600' : ($terbaru->nilai >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $terbaru->nilai }}
                            </span>
                        @else
                            <span class="text-orange-500 italic text-xs">Belum dinilai</span>
                        @endif
                    @else
                        <span class="text-gray-400 italic">Belum ada</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center">
                    @if($rataRata !== null)
                        <span class="inline-block px-3 py-1 bg-gray-100 rounded-lg font-bold {{ $rataRata >= 75 ? 'text-green-600' : ($rataRata >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                            {{ $rataRata }}
                        </span>
                    @else
                        <span class="text-gray-400">—</span>
                    @endif
                </td>
                <td class="px-5 py-4 text-center text-gray-600">
                    {{ $nilaiList->count() }} Tugas
                </td>
                <td class="px-5 py-4 text-right">
                    <a href="{{ route('guru.rekap-nilai.detail', ['siswa_id' => $siswa->id, 'kelas_id' => $kelasId, 'mata_pelajaran_id' => $mataPelajaranId, 'periode_id' => $periodeId]) }}" 
                       class="text-primary hover:text-primary-dark font-medium text-sm inline-flex items-center gap-1 transition-colors">
                        Lihat Detail
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

@if($siswaList->hasPages())
    {{ $siswaList->links() }}
@endif

@endif
@endsection
