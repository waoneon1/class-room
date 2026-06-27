@extends('admin.layout')

@section('title', 'Edit Pengguna')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.pengguna.index') }}" class="text-sm text-gray-500 hover:text-primary flex items-center gap-1 mb-3 w-fit">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali
    </a>
    <h1 class="text-xl font-bold text-gray-800">Edit Pengguna</h1>
</div>

<div class="bg-white rounded-2xl shadow-sm p-6 max-w-xl">
    <form method="POST" action="{{ route('admin.pengguna.update', $pengguna) }}">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $pengguna->nama_lengkap) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('nama_lengkap') border-red-400 @enderror"
                    required>
                @error('nama_lengkap')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Username</label>
                    <input type="text" name="username" value="{{ old('username', $pengguna->username) }}"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('username') border-red-400 @enderror"
                        required>
                    @error('username')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Role</label>
                    <select name="role" id="roleSelect"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('role') border-red-400 @enderror"
                        required onchange="toggleKelas(this.value)">
                        <option value="admin" {{ old('role', $pengguna->role) === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="guru"  {{ old('role', $pengguna->role) === 'guru'  ? 'selected' : '' }}>Guru</option>
                        <option value="siswa" {{ old('role', $pengguna->role) === 'siswa' ? 'selected' : '' }}>Siswa</option>
                    </select>
                    @error('role')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                <input type="email" name="email" value="{{ old('email', $pengguna->email) }}"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('email') border-red-400 @enderror"
                    required>
                @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div id="kelasField" class="{{ old('role', $pengguna->role) === 'siswa' ? '' : 'hidden' }}">
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                <select name="kelas_id"
                    class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('kelas_id') border-red-400 @enderror">
                    <option value="">Pilih kelas</option>
                    @foreach($kelas as $k)
                        <option value="{{ $k->id }}" {{ old('kelas_id', $pengguna->kelas_id) == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>
                    @endforeach
                </select>
                @error('kelas_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Password Baru <span class="text-gray-400 font-normal">(opsional)</span></label>
                    <input type="password" name="password"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary @error('password') border-red-400 @enderror"
                        placeholder="Kosongkan jika tidak diubah">
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation"
                        class="w-full border border-gray-200 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-primary"
                        placeholder="Ulangi password baru">
                </div>
            </div>
        </div>

        <div class="flex gap-3 mt-6">
            <button type="submit"
                class="bg-primary hover:bg-primary-dark text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Perbarui
            </button>
            <a href="{{ route('admin.pengguna.index') }}"
               class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function toggleKelas(role) {
    document.getElementById('kelasField').classList.toggle('hidden', role !== 'siswa');
}
</script>
@endsection
