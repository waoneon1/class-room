<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Periode;

class KelasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       
        
        Kelas::create(['nama_kelas' => 'Kelas 4A']);
        Kelas::create(['nama_kelas' => 'Kelas 4B']);
    }
}
