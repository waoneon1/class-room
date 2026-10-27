@extends('guru.layout')

@section('title', 'Detail Nilai ' . $siswa->nama_lengkap)

@section('content')
<div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Detail Nilai</h1>
        <p class="text-sm text-gray-500 mt-0.5">{{ $siswa->nama_lengkap }} — {{ $mapel->nama_pelajaran }}</p>
    </div>
    
    <a href="{{ route('guru.rekap-nilai.index', request()->only(['kelas_id', 'mata_pelajaran_id', 'periode_id'])) }}" 
       class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors inline-flex items-center gap-2 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
        Kembali ke Rekap
    </a>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden mb-6">
    <table class="w-full text-sm">
        <thead>
            <tr class="bg-gray-50 border-b border-gray-100">
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Judul Tugas</th>
                <th class="text-left px-5 py-3 text-gray-500 font-medium">Batas Waktu</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Status Pengumpulan</th>
                <th class="text-center px-5 py-3 text-gray-500 font-medium">Nilai</th>
                <th class="text-right px-5 py-3 text-gray-500 font-medium">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($semuaTugas as $tugas)
                @php 
                    $kumpul = $pengumpulan->get($tugas->id); 
                @endphp
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <p class="font-medium text-gray-800">{{ $tugas->judul }}</p>
                    </td>
                    <td class="px-5 py-4 text-gray-600">
                        {{ $tugas->deadline ? $tugas->deadline->format('d M Y, H:i') : 'Tanpa batas waktu' }}
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($kumpul)
                            @if($kumpul->terlambat)
                                <span class="bg-orange-100 text-orange-700 text-xs px-3 py-1 rounded-full font-medium">Dikumpulkan (Terlambat)</span>
                            @else
                                <span class="bg-green-100 text-green-700 text-xs px-3 py-1 rounded-full font-medium">Dikumpulkan</span>
                            @endif
                        @else
                            <span class="bg-red-50 text-red-600 text-xs px-3 py-1 rounded-full font-medium">Belum/Tidak Mengumpulkan</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        @if($kumpul && $kumpul->nilai !== null)
                            <span class="font-bold text-lg {{ $kumpul->nilai >= 75 ? 'text-green-600' : ($kumpul->nilai >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $kumpul->nilai }}
                            </span>
                        @elseif($kumpul)
                            <span class="text-gray-400 italic">Belum dinilai</span>
                        @else
                            <span class="text-gray-400">—</span>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-right">
                        @if($kumpul)
                            <a href="{{ route('guru.pengumpulan.show', $kumpul->id) }}" class="text-primary hover:text-primary-dark font-medium text-sm transition-colors">Lihat Berkas / Nilai</a>
                        @else
                            <span class="text-gray-300 text-sm cursor-not-allowed">Tak ada berkas</span>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="px-5 py-8 text-center text-gray-500">Belum ada tugas yang diberikan untuk mata pelajaran ini.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection