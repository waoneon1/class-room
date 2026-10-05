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
    public function index(Request $request)
    {
        $query = User::with(['rombel' => function($q) {
            $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
            if ($periodeId) {
                $q->wherePivot('periode_id', $periodeId);
            }
        }]);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nama_lengkap', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $pengguna = $query->orderBy('role')->orderBy('nama_lengkap')->paginate(15)->withQueryString();
        
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
            'tipe_guru'    => 'nullable|in:wali,mapel',
            'kelas_id'     => 'nullable|array',
            'kelas_id.*'   => 'exists:kelas,id',
        ]);

        $data['password'] = Hash::make($data['password']);
        
        $kelasId = $data['kelas_id'] ?? [];
        unset($data['kelas_id']); // Not in fillable anymore
        
        $tipe_guru = $data['tipe_guru'] ?? null;
        if ($data['role'] !== 'guru') {
            $data['tipe_guru'] = null;
            $tipe_guru = null;
        }

        $user = User::create($data);
        $user->assignRole($data['role']);

        if ($data['role'] === 'siswa' && count($kelasId) > 0) {
            $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
            if ($periodeId) {
                // Siswa hanya 1 kelas
                $user->rombel()->attach($kelasId[0], ['periode_id' => $periodeId]);
            }
        } elseif ($data['role'] === 'guru' && count($kelasId) > 0) {
            $user->mengajarKelas()->sync($kelasId);
            
            if ($tipe_guru === 'wali') {
                $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
                if ($periodeId) {
                    $mapels = \App\Models\MataPelajaran::pluck('id');
                    $newJadwal = [];
                    foreach ($kelasId as $kId) {
                        $existingMapels = \App\Models\JadwalMengajar::where('kelas_id', $kId)
                            ->where('periode_id', $periodeId)
                            ->pluck('mata_pelajaran_id')
                            ->toArray();
                        
                        foreach ($mapels as $mapelId) {
                            if (!in_array($mapelId, $existingMapels)) {
                                $newJadwal[] = [
                                    'user_id' => $user->id,
                                    'kelas_id' => $kId,
                                    'mata_pelajaran_id' => $mapelId,
                                    'periode_id' => $periodeId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                    if (count($newJadwal) > 0) {
                        \App\Models\JadwalMengajar::insert($newJadwal);
                    }
                }
            }
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
        $pengguna = User::findOrFail($id);
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
            'tipe_guru'    => 'nullable|in:wali,mapel',
            'kelas_id'     => 'nullable|array',
            'kelas_id.*'   => 'exists:kelas,id',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $kelasId = $data['kelas_id'] ?? [];
        unset($data['kelas_id']);
        
        $tipe_guru = $data['tipe_guru'] ?? null;
        if ($data['role'] !== 'guru') {
            $data['tipe_guru'] = null;
            $tipe_guru = null;
        }

        $pengguna->update($data);
        $pengguna->syncRoles([$data['role']]);

        if ($data['role'] === 'siswa' && count($kelasId) > 0) {
            $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
            if ($periodeId) {
                // Hapus rombel di periode aktif, lalu set yang baru
                $pengguna->rombel()->wherePivot('periode_id', $periodeId)->detach();
                $pengguna->rombel()->attach($kelasId[0], ['periode_id' => $periodeId]);
            }
        } elseif ($data['role'] === 'guru' && count($kelasId) > 0) {
            $pengguna->mengajarKelas()->sync($kelasId);

            if ($tipe_guru === 'wali') {
                $periodeId = \App\Models\Periode::where('is_active', true)->value('id');
                if ($periodeId) {
                    $mapels = \App\Models\MataPelajaran::pluck('id');
                    
                    $newJadwal = [];
                    foreach ($kelasId as $kId) {
                        $existingMapels = \App\Models\JadwalMengajar::where('kelas_id', $kId)
                            ->where('periode_id', $periodeId)
                            ->pluck('mata_pelajaran_id')
                            ->toArray();
                        
                        foreach ($mapels as $mapelId) {
                            if (!in_array($mapelId, $existingMapels)) {
                                $newJadwal[] = [
                                    'user_id' => $pengguna->id,
                                    'kelas_id' => $kId,
                                    'mata_pelajaran_id' => $mapelId,
                                    'periode_id' => $periodeId,
                                    'created_at' => now(),
                                    'updated_at' => now(),
                                ];
                            }
                        }
                    }
                    if (count($newJadwal) > 0) {
                        \App\Models\JadwalMengajar::insert($newJadwal);
                    }
                }
            }
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
