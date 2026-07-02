<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\MataPelajaran;

class MataPelajaranSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        MataPelajaran::create(['nama_pelajaran' => 'Matematika']);
        MataPelajaran::create(['nama_pelajaran' => 'Bahasa Indonesia']);
        MataPelajaran::create(['nama_pelajaran' => 'PKN']);
        MataPelajaran::create(['nama_pelajaran' => 'IPAS']);
        MataPelajaran::create(['nama_pelajaran' => 'Pendidikan Agama']);
        MataPelajaran::create(['nama_pelajaran' => 'Bahasa Inggris']);
    }
}
