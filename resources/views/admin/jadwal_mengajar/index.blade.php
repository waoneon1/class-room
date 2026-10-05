@extends('admin.layout')

@section('title', 'Jadwal Mengajar')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Jadwal Mengajar</h1>
        <p class="text-sm text-gray-500 mt-0.5">Pilih kelas untuk mengatur guru mata pelajaran</p>
    </div>
</div>

@if(!$periodeAktif)
<div class="bg-red-50 text-red-500 p-4 rounded-xl mb-4">
    Belum ada Periode Akademik yang aktif. Silakan atur di menu Periode.
</div>
@endif

<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    @foreach($kelas as $k)
    <a href="{{ route('admin.jadwal-mengajar.show', $k->id) }}" class="bg-white rounded-2xl shadow-sm p-5 hover:shadow-md transition-shadow border border-gray-100 flex items-center justify-between group">
        <div>
            <h3 class="font-bold text-gray-800 text-lg">{{ $k->nama_kelas }}</h3>
            <p class="text-sm text-gray-500 mt-1">Atur Pengajar</p>
        </div>
        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center text-gray-400 group-hover:bg-primary-light group-hover:text-primary transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </div>
    </a>
    @endforeach
</div>
@endsection
