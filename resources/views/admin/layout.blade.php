<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') — SDN Ketintang II</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#F7F7F7] min-h-screen flex flex-col">

    {{-- Topbar --}}
    <div class="bg-primary h-16 flex items-center px-4 md:px-6 gap-4 fixed top-0 left-0 right-0 z-20">
        <button id="sidebarToggle" class="md:hidden text-white p-1 rounded-lg hover:bg-white/10 shrink-0">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>
        <div class="flex items-center gap-3 shrink-0">
            <div class="w-8 h-8 bg-white/20 rounded-lg items-center justify-center hidden md:flex">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <span class="text-white font-bold text-base">Classroom</span>
        </div>
        <div class="flex-1">
            <span class="text-primary-light text-sm hidden md:inline">SDN Ketintang II Surabaya</span>
        </div>
        <div class="flex items-center gap-2 md:gap-3">
            <div class="text-right hidden sm:block">
                <p class="text-white text-sm font-medium">{{ auth()->user()->nama_lengkap }}</p>
                <p class="text-primary-light text-xs">Administrator</p>
            </div>
            <div class="w-9 h-9 bg-white/20 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                {{ strtoupper(substr(auth()->user()->nama_lengkap, 0, 1)) }}
            </div>
        </div>
    </div>

    {{-- Overlay backdrop (mobile) --}}
    <div id="sidebarBackdrop" class="fixed inset-0 bg-black/40 z-10 hidden md:hidden" onclick="closeSidebar()"></div>

    <div class="flex pt-16 min-h-screen">
        {{-- Sidebar --}}
        <aside id="sidebar" class="w-[220px] bg-primary min-h-screen fixed left-0 top-16 bottom-0 z-20 flex flex-col py-6 -translate-x-full md:translate-x-0 transition-all duration-200">
            <nav class="flex-1 px-3 space-y-1">
                <a href="{{ route('admin.pengguna.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.pengguna*') ? 'bg-white/20 text-white font-semibold' : 'text-primary-light hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span class="sidebar-label text-sm">Pengguna</span>
                </a>

                <a href="{{ route('admin.kelas.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.kelas*') ? 'bg-white/20 text-white font-semibold' : 'text-primary-light hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                    <span class="sidebar-label text-sm">Kelas</span>
                </a>

                <a href="{{ route('admin.mata-pelajaran.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.mata-pelajaran*') ? 'bg-white/20 text-white font-semibold' : 'text-primary-light hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="sidebar-label text-sm">Mata Pelajaran</span>
                </a>

                <a href="{{ route('admin.rekap-nilai.index') }}"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors {{ request()->routeIs('admin.rekap-nilai*') ? 'bg-white/20 text-white font-semibold' : 'text-primary-light hover:bg-white/10 hover:text-white' }}">
                    <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span class="sidebar-label text-sm">Rekap Nilai</span>
                </a>
            </nav>

            {{-- Logout + Collapse --}}
            <div class="px-3 mt-4 space-y-1">
                <form method="POST" action="/logout">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-primary-light hover:bg-white/10 hover:text-white transition-colors">
                        <svg class="w-5 h-5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sidebar-label text-sm">Keluar</span>
                    </button>
                </form>
                <button id="collapseBtn" onclick="toggleCollapse()"
                    class="hidden md:flex w-full items-center gap-3 px-4 py-2.5 rounded-xl text-primary-light hover:bg-white/10 hover:text-white transition-colors">
                    <svg id="collapseIcon" class="w-5 h-5 shrink-0 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 19l-7-7 7-7m8 14l-7-7 7-7"/>
                    </svg>
                    <span class="sidebar-label text-xs">Sembunyikan</span>
                </button>
            </div>
        </aside>

        {{-- Main Content --}}
        <main id="mainContent" class="flex-1 ml-0 md:ml-[220px] p-4 md:p-6">
            {{-- Page Header --}}
            @if(isset($header))
                <div class="mb-6">
                    {{ $header }}
                </div>
            @endif

            {{-- Flash Messages --}}
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
    </div>

<script>
    const sidebar = document.getElementById('sidebar');
    const backdrop = document.getElementById('sidebarBackdrop');
    const mainContent = document.getElementById('mainContent');
    const collapseIcon = document.getElementById('collapseIcon');

    document.getElementById('sidebarToggle').addEventListener('click', () => {
        sidebar.classList.toggle('-translate-x-full');
        backdrop.classList.toggle('hidden');
    });

    function closeSidebar() {
        sidebar.classList.add('-translate-x-full');
        backdrop.classList.add('hidden');
    }

    let collapsed = localStorage.getItem('sidebar_collapsed') === '1';
    function applyCollapse() {
        if (collapsed) {
            sidebar.style.width = '64px';
            mainContent.style.marginLeft = '64px';
            collapseIcon.style.transform = 'rotate(180deg)';
            document.querySelectorAll('.sidebar-label').forEach(el => el.classList.add('hidden'));
        } else {
            sidebar.style.width = '220px';
            mainContent.style.marginLeft = '220px';
            collapseIcon.style.transform = 'rotate(0deg)';
            document.querySelectorAll('.sidebar-label').forEach(el => el.classList.remove('hidden'));
        }
    }

    function toggleCollapse() {
        collapsed = !collapsed;
        localStorage.setItem('sidebar_collapsed', collapsed ? '1' : '0');
        applyCollapse();
    }

    if (window.innerWidth >= 768) applyCollapse();
</script>
</body>
</html>
