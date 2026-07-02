<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::with('kelas')->orderBy('role')->orderBy('nama_lengkap')->get();
        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        $kelas = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pengguna.create', compact('kelas'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => 'required|string|max:50|unique:users,username',
            'email'        => 'required|email|unique:users,email',
            'password'     => 'required|string|min:6|confirmed',
            'role'         => 'required|in:admin,guru,siswa',
            'kelas_id'     => 'nullable|exists:kelas,id',
            'mengajar_kelas_id' => 'nullable|array',
            'mengajar_kelas_id.*' => 'exists:kelas,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        $user = User::create($data);
        $user->assignRole($data['role']);

        if ($data['role'] === 'guru' && !empty($data['mengajar_kelas_id'])) {
            $user->mengajarKelas()->sync($data['mengajar_kelas_id']);
        }

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function show($id)
    {
        $pengguna = User::withTrashed()->findOrFail($id);
        return redirect()->route('admin.pengguna.edit', $pengguna);
    }

    public function edit($id)
    {
        $pengguna = User::with('mengajarKelas')->findOrFail($id);
        $kelas    = Kelas::orderBy('nama_kelas')->get();
        return view('admin.pengguna.edit', compact('pengguna', 'kelas'));
    }

    public function update(Request $request, $id)
    {
        $pengguna = User::findOrFail($id);

        $data = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username'     => ['required', 'string', 'max:50', Rule::unique('users', 'username')->ignore($pengguna->id)],
            'email'        => ['required', 'email', Rule::unique('users', 'email')->ignore($pengguna->id)],
            'password'     => 'nullable|string|min:6|confirmed',
            'role'         => 'required|in:admin,guru,siswa',
            'kelas_id'     => 'nullable|exists:kelas,id',
            'mengajar_kelas_id' => 'nullable|array',
            'mengajar_kelas_id.*' => 'exists:kelas,id',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $pengguna->update($data);
        $pengguna->syncRoles([$data['role']]);

        if ($data['role'] === 'guru') {
            $pengguna->mengajarKelas()->sync($data['mengajar_kelas_id'] ?? []);
        } else {
            $pengguna->mengajarKelas()->sync([]);
        }

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $pengguna = User::findOrFail($id);
        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
