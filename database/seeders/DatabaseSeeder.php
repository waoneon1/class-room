<?php

namespace Database\Seeders;

use App\Models\Kelas;
use App\Models\MataPelajaran;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'guru', 'guard_name' => 'web']);
        Role::firstOrCreate(['name' => 'siswa', 'guard_name' => 'web']);

        $kelas = Kelas::create(['nama_kelas' => 'Kelas 4A']);

        MataPelajaran::create(['nama_pelajaran' => 'Matematika']);
        MataPelajaran::create(['nama_pelajaran' => 'Bahasa Indonesia']);

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

        $siswa1 = User::create([
            'nama_lengkap' => 'Andi Pratama',
            'username'     => 'siswa1',
            'email'        => 'siswa1@sekolah.com',
            'password'     => bcrypt('password'),
            'role'         => 'siswa',
            'kelas_id'     => $kelas->id,
        ]);
        $siswa1->assignRole('siswa');

        $siswa2 = User::create([
            'nama_lengkap' => 'Siti Rahayu',
            'username'     => 'siswa2',
            'email'        => 'siswa2@sekolah.com',
            'password'     => bcrypt('password'),
            'role'         => 'siswa',
            'kelas_id'     => $kelas->id,
        ]);
        $siswa2->assignRole('siswa');
    }
}
