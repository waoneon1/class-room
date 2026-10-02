<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\Periode;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        $admin = User::create([
            'nama_lengkap' => 'Administrator',
            'username'     => 'admin',
            'email'        => 'admin@sekolah.com',
            'password'     => bcrypt('password'),
            'role'         => 'admin',
        ]);
        $admin->assignRole('admin');

        $guru = User::create([
            'nama_lengkap' => 'Budi Santoso',
            'username'     => 'guru',
            'email'        => 'guru@sekolah.com',
            'password'     => bcrypt('password'),
            'role'         => 'guru',
        ]);
        $guru->assignRole('guru');
        
        $kelas = Kelas::first();
        $mapel = MataPelajaran::first();
        $periode = Periode::first() ?? Periode::create([
            'nama_periode' => 'Ganjil 2026/2027',
            'is_active' => true
        ]);

        if ($kelas && $mapel && $periode) {
            $guru->jadwalMengajar()->create([
                'kelas_id' => $kelas->id,
                'mata_pelajaran_id' => $mapel->id,
                'periode_id' => $periode->id,
            ]);

            $siswa1 = User::create([
                'nama_lengkap' => 'Andi Pratama',
                'username'     => 'siswa1',
                'email'        => 'siswa1@sekolah.com',
                'password'     => bcrypt('password'),
                'role'         => 'siswa',
            ]);
            $siswa1->assignRole('siswa');
            $siswa1->rombel()->attach($kelas->id, ['periode_id' => $periode->id]);

            $siswa2 = User::create([
                'nama_lengkap' => 'Siti Rahayu',
                'username'     => 'siswa2',
                'email'        => 'siswa2@sekolah.com',
                'password'     => bcrypt('password'),
                'role'         => 'siswa',
            ]);
            $siswa2->assignRole('siswa');
            $siswa2->rombel()->attach($kelas->id, ['periode_id' => $periode->id]);
        }
    }
}
