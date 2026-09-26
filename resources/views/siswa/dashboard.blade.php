@extends('siswa.layout_topbar')

@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 xl:grid-cols-4 gap-x-6 gap-y-10">
    @php
        $patterns = ['path.svg', 'path-1.svg', 'path-2.svg', 'path-3.svg'];
    @endphp

    @forelse($mataPelajaran as $mp)
    @php
        $patternIndex = $loop->index % count($patterns);
        $pattern = $patterns[$patternIndex];
        $guruName = $gurusPerSubject[$mp->id] ?? 'Nama Guru';
    @endphp
    
    <a href="{{ route('siswa.dashboard.subject', $mp->id) }}" class="block group">
        <div class="flex flex-col h-full">
            {{-- Image Card --}}
            <div class="w-full h-44 rounded-[20px] bg-orange-50 border border-gray-100/50 shadow-sm overflow-hidden mb-4 group-hover:shadow-md transition-all duration-300 relative">
                <img src="{{ asset('images/pattern_assets/' . $pattern) }}" alt="Pattern" class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-500 ease-out mix-blend-multiply">
            </div>

            {{-- Info --}}
            <div class="px-1">
                <h3 class="font-bold text-gray-900 text-[19px] group-hover:text-primary transition-colors tracking-tight leading-tight">{{ $mp->nama_pelajaran }}.</h3>
                <p class="text-sm text-gray-400 mt-1 font-medium">{{ $guruName }}</p>
            </div>
        </div>
    </a>
    @empty
    <div class="col-span-full bg-white rounded-2xl shadow-sm p-10 text-center text-gray-400 text-sm">
        Belum ada mata pelajaran yang dibagikan untuk kelas kamu.
    </div>
    @endforelse
</div>
@endsection
