<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Siswa') — SDN Ketintang Surabaya</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F7F7F7] min-h-screen flex flex-col font-sans">

    @php
        $activeSubjectId = request('subject_id');
        if (!$activeSubjectId && isset($mataPelajaran) && $mataPelajaran instanceof \App\Models\MataPelajaran) {
            $activeSubjectId = $mataPelajaran->id;
        } elseif (!$activeSubjectId && isset($materi) && $materi instanceof \App\Models\Materi) {
            $activeSubjectId = $materi->mata_pelajaran_id;
        } elseif (!$activeSubjectId && isset($tugas) && $tugas instanceof \App\Models\Tugas) {
            $activeSubjectId = $tugas->mata_pelajaran_id;
        }
    @endphp

    {{-- Topbar --}}
    <div class="bg-primary h-20 flex items-center px-4 md:px-8 fixed top-0 left-0 right-0 z-20 justify-between">
        
        {{-- Left: Logo & School --}}
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-10 h-10 bg-white/20 rounded-xl items-center justify-center hidden md:flex">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <div class="flex flex-col">
                <span class="text-white font-bold text-lg leading-tight">Classroom</span>
                <span class="text-primary-light text-xs font-medium hidden md:block">SDN Ketintang Surabaya</span>
            </div>
        </div>

        {{-- Right side container: Navigation + Profile --}}
        <div class="flex items-center gap-6">
            {{-- Navigation (Desktop) --}}
            <div class="hidden md:flex items-center gap-8">
                <a href="{{ route('siswa.dashboard') }}" class="text-sm font-semibold {{ request()->routeIs('siswa.dashboard') ? 'text-white' : 'text-primary-light hover:text-white' }} transition-colors">Home</a>
                
                @if($activeSubjectId)
                <a href="{{ route('siswa.materi.index', ['subject_id' => $activeSubjectId]) }}" class="text-sm font-semibold {{ request()->routeIs('siswa.materi*') ? 'text-white' : 'text-primary-light hover:text-white' }} transition-colors">Materi</a>
                <a href="{{ route('siswa.tugas.index', ['subject_id' => $activeSubjectId]) }}" class="text-sm font-semibold {{ request()->routeIs('siswa.tugas*') ? 'text-white' : 'text-primary-light hover:text-white' }} transition-colors">Tugas</a>
                @endif

                <a href="{{ $activeSubjectId ? route('siswa.nilai.index', ['subject_id' => $activeSubjectId]) : route('siswa.nilai.index') }}" class="text-sm font-semibold {{ request()->routeIs('siswa.nilai*') ? 'text-white' : 'text-primary-light hover:text-white' }} transition-colors">Rekap Nilai</a>
            </div>

            {{-- Divider --}}
            <div class="hidden md:block w-px h-8 bg-primary-light/30"></div>

            {{-- Right: Profile & Logout --}}
            <div class="flex items-center shrink-0 border border-primary-light/30 rounded-full pl-1 pr-4 py-1 gap-3 relative group cursor-pointer hover:bg-white/10 transition-colors">
                <div class="w-8 h-8 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                    {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
                </div>
                <span class="text-white text-xs font-medium hidden sm:block">{{ auth()->user()->nama_lengkap }}</span>

                {{-- Dropdown Logout --}}
                <div class="absolute right-0 top-full mt-2 w-32 bg-white rounded-xl shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all border border-gray-100">
                    <form method="POST" action="/logout">
                        @csrf
                        <button type="submit" class="w-full text-left px-4 py-3 text-sm text-red-600 hover:bg-red-50 rounded-xl font-semibold">Keluar</button>
                    </form>
                </div>
            </div>
            
            {{-- Mobile Nav Toggle --}}
            <button id="mobileMenuBtn" class="md:hidden text-white p-1 ml-2">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
        </div>
    </div>

    {{-- Mobile Nav Menu --}}
    <div id="mobileMenu" class="hidden fixed top-20 left-0 right-0 bg-primary z-10 p-4 border-t border-primary-light/20 flex-col gap-4 shadow-lg md:hidden">
        <a href="{{ route('siswa.dashboard') }}" class="text-sm font-semibold {{ request()->routeIs('siswa.dashboard') ? 'text-white' : 'text-primary-light' }}">Home</a>
        @if($activeSubjectId)
        <a href="{{ route('siswa.materi.index', ['subject_id' => $activeSubjectId]) }}" class="text-sm font-semibold {{ request()->routeIs('siswa.materi*') ? 'text-white' : 'text-primary-light' }}">Materi</a>
        <a href="{{ route('siswa.tugas.index', ['subject_id' => $activeSubjectId]) }}" class="text-sm font-semibold {{ request()->routeIs('siswa.tugas*') ? 'text-white' : 'text-primary-light' }}">Tugas</a>
        @endif
        <a href="{{ $activeSubjectId ? route('siswa.nilai.index', ['subject_id' => $activeSubjectId]) : route('siswa.nilai.index') }}" class="text-sm font-semibold {{ request()->routeIs('siswa.nilai*') ? 'text-white' : 'text-primary-light' }}">Rekap Nilai</a>
        
        <form method="POST" action="/logout" class="mt-2 pt-4 border-t border-primary-light/20">
            @csrf
            <button type="submit" class="text-sm text-red-400 font-semibold w-full text-left">Keluar</button>
        </form>
    </div>

    {{-- Main Content --}}
    <main class="flex-1 mt-20 p-4 md:p-8 w-full">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 text-sm rounded-xl px-4 py-3 mb-5 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

<script>
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const mobileMenu = document.getElementById('mobileMenu');

    if (mobileMenuBtn && mobileMenu) {
        mobileMenuBtn.addEventListener('click', () => {
            if (mobileMenu.classList.contains('hidden')) {
                mobileMenu.classList.remove('hidden');
                mobileMenu.classList.add('flex');
            } else {
                mobileMenu.classList.add('hidden');
                mobileMenu.classList.remove('flex');
            }
        });
    }
</script>
</body>
</html>
