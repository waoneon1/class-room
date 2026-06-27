<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengumpulan extends Model
{
    protected $table = 'pengumpulan';

    protected $fillable = [
        'tugas_id',
        'siswa_id',
        'status',
        'terlambat',
        'nilai',
        'catatan',
    ];

    protected function casts(): array
    {
        return [
            'terlambat' => 'boolean',
        ];
    }

    public function tugas()
    {
        return $this->belongsTo(Tugas::class);
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }

    public function files()
    {
        return $this->hasMany(PengumpulanFile::class)->orderBy('urutan');
    }
}
