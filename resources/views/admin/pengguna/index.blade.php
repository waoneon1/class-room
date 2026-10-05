@extends('admin.layout')

@section('title', 'Kelola Pengguna')

@section('content')
<div class="flex flex-col md:flex-row items-center justify-between mb-6 gap-4">
    <div>
        <h1 class="text-xl font-bold text-gray-800">Kelola Pengguna</h1>
        <p class="text-sm text-gray-500 mt-0.5">Daftar seluruh pengguna sistem</p>
    </div>
    <div class="flex items-center gap-3 w-full md:w-auto">
        <form action="{{ route('admin.pengguna.index') }}" method="GET" class="flex flex-1 md:flex-none gap-2">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama/username..." 
                class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary w-full md:w-64">
            <select name="role" class="border border-gray-200 rounded-xl px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-primary bg-white" onchange="this.form.submit()">
                <option value="">Semua Role</option>
                <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ request('role') == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="siswa" {{ request('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
            </select>
            <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-600 px-3 py-2 rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </button>
        </form>
        <a href="{{ route('admin.pengguna.create') }}"
           class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-5 py-2.5 rounded-xl transition-colors flex items-center gap-2 shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span class="hidden sm:inline">Tambah Pengguna</span>
        </a>
    </div>
</div>

<div class="bg-white rounded-2xl shadow-sm overflow-hidden">
    <table class="w-full text-sm">
        <thead>
            <tr class="border-b border-gray-100">
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Nama</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Username</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Email</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Role</th>
                <th class="text-left px-5 py-3.5 text-gray-500 font-medium">Kelas</th>
                <th class="px-5 py-3.5"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pengguna as $user)
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-5 py-3.5">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 bg-primary-light rounded-full flex items-center justify-center text-primary font-bold text-xs">
                            {{ strtoupper(substr($user->nama_lengkap, 0, 1)) }}
                        </div>
                        <span class="font-medium text-gray-800">{{ $user->nama_lengkap }}</span>
                    </div>
                </td>
                <td class="px-5 py-3.5 text-gray-600">{{ $user->username }}</td>
                <td class="px-5 py-3.5 text-gray-600">{{ $user->email }}</td>
                <td class="px-5 py-3.5">
                    @if($user->role === 'admin')
                        <span class="bg-purple-100 text-purple-700 text-xs px-2.5 py-1 rounded-full font-medium">Admin</span>
                    @elseif($user->role === 'guru')
                        <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full font-medium">Guru</span>
                    @else
                        <span class="bg-primary-light text-primary text-xs px-2.5 py-1 rounded-full font-medium">Siswa</span>
                    @endif
                </td>
                <td class="px-5 py-3.5 text-gray-600">{{ $user->kelas?->nama_kelas ?? '—' }}</td>
                <td class="px-5 py-3.5">
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.pengguna.edit', $user) }}"
                           class="text-gray-400 hover:text-primary transition-colors p-1.5 rounded-lg hover:bg-primary-light">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </a>
                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.pengguna.destroy', $user) }}"
                              onsubmit="return confirm('Hapus pengguna {{ $user->nama_lengkap }}?')">
                            @csrf @method('DELETE')
                            <button type="submit"
                                class="text-gray-400 hover:text-red-500 transition-colors p-1.5 rounded-lg hover:bg-red-50">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="px-5 py-8 text-center text-gray-500">Tidak ada data pengguna ditemukan.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    @if($pengguna->hasPages())
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $pengguna->links() }}
    </div>
    @endif
</div>
@endsection
