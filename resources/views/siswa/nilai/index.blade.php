@extends('siswa.layout_topbar')

@section('title', 'Rekap Nilai')

@section('content')
<div class="mb-6">
    <h1 class="text-xl font-bold text-gray-800">Rekap Nilai Keseluruhan</h1>
    <p class="text-sm text-gray-500 mt-0.5">Rata-rata nilai per mata pelajaran</p>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
    @foreach($rekap as $r)
    <a href="{{ route('siswa.nilai.index', ['subject_id' => $r['mata_pelajaran']->id]) }}" class="block group">
        <div class="bg-white rounded-2xl shadow-sm p-6 hover:shadow-md hover:border-primary border border-transparent transition-all h-full flex flex-col">
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <h3 class="font-bold text-gray-800 group-hover:text-primary transition-colors">{{ $r['mata_pelajaran']->nama_pelajaran }}</h3>
                </div>
            </div>
            
            <div class="mt-auto flex items-end justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Tugas Dinilai</p>
                    <p class="text-sm font-semibold text-gray-700">{{ $r['jumlah_tugas'] }} Tugas</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500 mb-1">Rata-rata</p>
                    @if($r['rata_rata'] !== null)
                    <p class="text-2xl font-bold {{ $r['rata_rata'] >= 75 ? 'text-green-600' : ($r['rata_rata'] >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                        {{ number_format($r['rata_rata'], 1) }}
                    </p>
                    @else
                    <p class="text-xl font-bold text-gray-300">-</p>
                    @endif
                </div>
            </div>
        </div>
    </a>
    @endforeach
</div>
@endsection
